<?php

namespace Kanboard\Plugin\SimplerFilters\Helper;

use Kanboard\Core\Base;

/**
 * Class FilterHelper
 * * Provides data retrieval methods for the search dropdowns.
 * Fetches Tags, Users, and Priorities specific to a project.
 *
 * @package Kanboard\Plugin\SimplerFilters\Helper
 */
class FilterHelper extends Base
{
    /**
     * Retrieve all tags associated with a specific project.
     *
     * @param  int $project_id The project ID.
     * @return array Associative array [tag_id => tag_name]
     */
    public function getProjectTags($project_id)
    {
        // Fetch all tag objects
        $tags = $this->tagModel->getAllByProject($project_id);
        // Convert to a simple [id => name] array for the select input
        return array_column($tags, 'name', 'id');
    }

    /**
     * Retrieve a list of users that can be assigned to tasks in the project.
     *
     * @param  int $project_id The project ID.
     * @return array Associative array [user_id => user_name]
     */
    public function getProjectUsers($project_id)
    {
        return $this->projectUserRoleModel->getAssignableUsersList($project_id);
    }

    /**
     * Retrieve priorities for the project with custom labeling.
     * * Maps numeric priorities to business labels (OK, INFO, etc.).
     *
     * @param  int $project_id The project ID.
     * @return array Associative array [priority_value => label]
     */
    public function getProjectPriorities($project_id)
    {
        $project = $this->projectModel->getById($project_id);
        
        $priorities = [];
        $start = $project['priority_start'] ?? 0;
        $end = $project['priority_end'] ?? 3;

        // Configuration of labels and colors for each priority level
        $priorityConfig = [
            0 => ['label' => t('Solved'), 'color' => 'priority-green'],
            1 => ['label' => t('Weak'),   'color' => 'priority-yellow'],
            2 => ['label' => t('Medium'), 'color' => 'priority-orange'],
            3 => ['label' => t('High'),   'color' => 'priority-red']
        ];
    
        for ($i = $start; $i <= $end; $i++) {
            if (isset($priorityConfig[$i])) {
                $priorities[$i] = $priorityConfig[$i];
            } else {
                $priorities[$i] = [
                    'label' => t('Priority') . ' ' . $i,
                    'color' => 'priority-default'
                ];
            }
        }

        return $priorities;
    }

    /**
     * Render the "Create Task/Alert" button.
     *
     * @param  int $project_id The project ID.
     * @return string HTML content of the button or empty string.
     */
    public function renderCreateButton($project_id)
    {
        $columnId = $this->columnModel->getFirstColumnId($project_id);
        $swimlaneId = $this->swimlaneModel->getFirstActiveSwimlaneId($project_id);

        if (empty($columnId) || empty($swimlaneId)) {
            return ''; 
        }

        // Utilisation de t('Create alert')
        return $this->helper->url->link(
            '<i class="fa fa-plus js-modal-large"></i> ' . t('Create alert'), 
            'TaskCreationController',
            'show',
            array(
                'project_id'  => $project_id,
                'column_id'   => $columnId,
                'swimlane_id' => $swimlaneId,
            ),
            false,
            'global-create-btn js-modal-large'
        );
    }

    /**
     * Retrieve all columns for a specific project.
     *
     * @param  int $project_id
     * @return array
     */
    public function getProjectColumns($project_id)
    {
        return $this->columnModel->getList($project_id);
    }
}