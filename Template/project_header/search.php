<div class="filter-box-component" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
    
    <?php if (isset($project['id'])): ?>
        <?php
        /* --- Data Retrieval --- */
        $tags = $this->helper->simplerFilters->getProjectTags($project['id']);
        $users = $this->helper->simplerFilters->getProjectUsers($project['id']);
        $priorities = $this->helper->simplerFilters->getProjectPriorities($project['id']);
        $columns = $this->helper->simplerFilters->getProjectColumns($project['id']);
        
        $last_week = date('Y-m-d', strtotime('-7 days')); 
        $last_month = date('Y-m-d', strtotime('-30 days'));

        $dates = [
            "created:today" => t('Created today'),
            "created:yesterday" => t('Created yesterday'),
            "created:>=$last_week" => t('Created this week'),
            "created:>=$last_month" => t('Created this month'),
        ];

        $current_search = $filters['search'] ?? '';
        $currentController = $this->app->request->getStringParam('controller');
        $targetController = in_array($currentController, ['TaskListController', 'BoardViewController', 'CalendarController', 'TaskGanttController']) ? $currentController : 'BoardViewController';
        $baseUrl = $this->url->href($targetController, 'show', ['project_id' => $project['id']]);
        ?>

        <div class="filter-left-section">
            <?= $this->helper->simplerFilters->renderCreateButton($project['id']) ?>
        </div>

        <div class="filter-right-section">
            <div class="simpler-filter-wrapper" 
                 data-base-url="<?= $baseUrl ?>" 
                 data-current-search="<?= $this->text->e($current_search) ?>">

                <div class="simpler-text-filter">
                    <i class="fa fa-filter"></i>
                    <span><?= t('Filter by') ?> :</span>
                </div>
                
                <div class="simpler-dropdown sub-dropdown">
                    <div class="simpler-dropdown-toggle">
                        <div><i class="fa fa-tag"></i> <span><?= t('Choose a location...') ?></span></div>
                        <i class="fa fa-chevron-down"></i>
                    </div>
                    <ul class="simpler-dropdown-menu">
                        <?php foreach ($tags as $id => $name): ?>
                            <li data-value='tag:"<?= $this->text->e($name) ?>"' data-checked="false">
                                <span><?= $this->text->e($name) ?></span>
                                <i class="fa fa-square-o chk-icon"></i>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </div>

                <div class="simpler-dropdown sub-dropdown">
                    <div class="simpler-dropdown-toggle">
                        <div><i class="fa fa-signal"></i> <span><?= t('Choose a priority...') ?></span></div>
                        <i class="fa fa-chevron-down"></i>
                    </div>
                    <ul class="simpler-dropdown-menu">
                        <?php foreach ($priorities as $val => $data): ?>
                            <li data-value='priority:<?= $val ?>' data-checked="false">
                                <div style="display:flex; align-items:center;">
                                    <span class="priority-dot <?= $data['color'] ?>"></span>
                                    <?= $data['label'] ?>
                                </div>
                                <i class="fa fa-square-o chk-icon"></i>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </div>

                <div class="simpler-dropdown sub-dropdown">
                    <div class="simpler-dropdown-toggle">
                        <div><i class="fa fa-user"></i> <span><?= t('Choose a user...') ?></span></div>
                        <i class="fa fa-chevron-down"></i>
                    </div>
                    <ul class="simpler-dropdown-menu">
                        <li data-value='assignee:nobody' data-checked="false">
                            <span><?= t('Nobody assigned') ?></span>
                            <i class="fa fa-square-o chk-icon"></i>
                        </li>
                        <?php foreach ($users as $id => $name): ?>
                            <?php if (empty($name) || $name == t('Nobody assigned')) continue; ?>
                            <li data-value='assignee:"<?= $this->text->e($name) ?>"' data-checked="false">
                                <span><?= $this->text->e($name) ?></span>
                                <i class="fa fa-square-o chk-icon"></i>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </div>

                <div class="simpler-dropdown sub-dropdown">
                    <div class="simpler-dropdown-toggle">
                        <div><i class="fa fa-calendar"></i> <span><?= t('Choose a timeframe...') ?></span></div>
                        <i class="fa fa-chevron-down"></i>
                    </div>
                    <ul class="simpler-dropdown-menu">
                        <?php foreach ($dates as $query => $label): ?>
                            <li data-value='<?= $query ?>' data-checked="false">
                                <span><?= $label ?></span>
                                <i class="fa fa-square-o chk-icon"></i>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </div>

                <div class="apply-container">
                    <button type="button" class="apply-filters-btn-toggle">
                        <i class="fa fa-check"></i> <?= t('Apply') ?>
                    </button>
                    
                    <div class="column-selector-dropdown">
                        <div class="column-selector-header"><?= t('Select columns to display') ?></div>
                        <ul class="column-list">
                            <?php foreach ($columns as $column_id => $column_name): ?>
                                <li data-column-value='column:"<?= $this->text->e($column_name) ?>"' data-checked="true">
                                    <span><?= $this->text->e($column_name) ?></span>
                                    <i class="fa fa-check-square-o chk-icon"></i>
                                </li>
                            <?php endforeach ?>
                        </ul>
                        <button type="button" class="confirm-apply-btn">
                            <?= t('Validate') ?>
                        </button>
                    </div>
                </div>

                <button type="button" class="reset-filters-btn">
                    <?= t('Reset filters') ?>
                </button>
            </div>
        </div>
    <?php endif ?>
</div>