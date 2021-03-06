<style>
.column {
    float: left;
    width: 30%;
    padding: 10px;
}
    .row:after {
    content: "";
    display: table;
    clear: both;
}
</style>

<div class="page-header">
    <h2><?= t('Custom Fields') ?></h2>
</div>

<form id="metadata-type-creation-form" method="post" action="<?= $this->url->href('MetadataTypesController', 'config', ['plugin' => 'MetaMagik']) ?>" autocomplete="off">
    <?= $this->form->label(t('Name'), 'human_name') ?>
    <?= $this->form->text('human_name', $values, $errors, ['required']) ?>
    <p><?= e('You may not use spaces, but underscores will be converted to spaces for field labels, for those who care.') ?></p>
    <?= $this->form->label(t('Type'), 'data_type') ?>
    <?= $this->form->select('data_type', [
        ''        => '--',
        'text'    => 'Text',
        'textarea'    => 'Text Area',
        'list'    => 'Dropdown List',
        'radio'   => 'Radio List',
        'check'   => 'Checkbox Group',
        'users'   => 'User List',
        'table'   => 'Key-value from DB',
        'columneqcriteria'   => 'Column from DB, based on equals Criteria',
        'number'  => 'Number',
        'date'  => 'Date',
    ], $values, $errors, ['required']) ?>
    
    <?= $this->form->label(t('Options'), 'options') ?>
    <?= $this->form->text('options', $values, $errors) ?>
    <p><?= e('Example: <code>value1,value2,value3</code> for list types. For Key-value from DB: <code>tablename,keycolumn,valuecolumn</code>.') ?></p>
    <p><?= e('Example: For Column from DB, based on equals Criteria: <code>tablename,criteria_column,criteria,value_column</code>.') ?></p>
    <p><?= e('Example: For Numbers, anything in the Options field will show up before the value, for instance, to add a dollar sign before the number.') ?></p>

    <?= $this->form->label(t('Column'), 'column_number') ?>
    <?= $this->form->select('column_number', [
        '1' => '1',
        '2' => '2',
        '3' => '3',
    ], $values, $errors, ['required']) ?>

    <?= $this->form->label(t('Required'), 'is_required') ?>
    <?= $this->form->checkbox('is_required', t('Required'), 1, true) ?>
    
    <?= $this->form->label(t('Include as Footer Icon?'), 'footer_inc') ?>
    <?= $this->form->checkbox('footer_inc', t('Include'), 1, true) ?>
    
    <?php $projects = $this->task->projectModel->getAllByStatus(1);
          $projectList = array(0 => 'Global'); ?>
    <?php 
          foreach($projects as $project) { 
            $projectList[$project['id']] = $project['name'];
          } 
    ?>

    <?= $this->form->label(t('Project scope:'), 'attach_to') ?>
    <?= $this->form->select('attached_to', $projectList, $values, $errors, ['required']) ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-blue"><?= t('Save') ?></button>
    </div>

    <?= $this->form->csrf() ?>
</form>
<hr>

<?php if (!empty($types)): ?>
<div class="row">
<?php for ($i = 1; $i <=3; $i++): ?>
<?php $x = 0 ?>
<div class="column">   
<table
       id="<?= $i ?>"
       class="metadata-table table-striped table-scrolling"
       data-save-position-url="<?= $this->url->href('MetadataTypesController', 'movePosition', array('plugin' => 'metaMagik')) ?>"
>
    <thead>
        <tr>
            <th><?= t('Field Name') ?></th>
            <th><?= t('Type') ?></th>
            <th><?= t('Options') ?></th>
            <th><?= t('Scope') ?></th>
            <th><?= t('Required') ?></th>
            <th><?= t('On Footer') ?></th>
            <th><?= t('Action') ?></th>
        </tr>
    </thead>
    <tbody id="<?= $i ?>" class="connected">
            <tr class="disabled">
                <td style="border: none"></td>
                <td style="border: none"></td>
                <td style="border: none"></td>
                <td style="border: none"></td>
                <td style="border: none"></td>
            </tr>
        <?php 
        foreach ($types as $type): 
        $key = $type['id']
        ?>
        <?php if ($type['column_number'] == $i): ?>
            <tr data-metadata-id="<?= $type['id'] ?>">
                <td>
                    <i class="fa fa-arrows-alt draggable-row-handle ui-sortable-handle" title="Change metadata position"></i>&nbsp;
                    <?= $type['human_name'] ?>
                </td>
                <td><?= $type['data_type'] ?></td>
                <td><?= $type['options'] ?></td>
                <td><?= $type['attached_to'] ?></td>
                <td><?= $type['is_required'] ?></td>
                <td><?= $type['footer_inc'] ?></td>
                <td>
                    <?= $this->modal->small('remove', t('Remove'), 'MetadataTypesController', 'confirmTask', ['plugin' => 'metaMagik', 'key' => $key], false, 'popover') ?>
                    <?= $this->modal->small('edit', t('Edit'), 'MetadataTypesController', 'editType', ['plugin' => 'metaMagik', 'key' => $key], false, 'popover') ?>
                </td>
            </tr>
        <?php endif ?>
        <?php endforeach ?>
</tbody>
</table>
</div>
<?php endfor ?>
</div>
<?php else: ?>
    <div class="listing">
        <?= t('No types have been defined yet.') ?>
    </div>
<?php endif ?>

