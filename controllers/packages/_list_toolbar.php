<div data-control="toolbar loader-container">
    <a
        href="<?= Backend::url('sixgweb/clubready/packages/create') ?>"
        class="btn btn-primary">
        <i class="icon-plus"></i>
        <?= __("New :name", ['name' => 'Package']) ?>
    </a>

    <div class="toolbar-divider"></div>

    <button
        class="btn btn-secondary"
        data-request="onDelete"
        data-request-message="<?= __("Deleting...") ?>"
        data-request-confirm="<?= __("Are you sure?") ?>"
        data-list-checked-trigger
        data-list-checked-request
        disabled>
        <i class="icon-delete"></i>
        <?= __("Delete") ?>
    </button>

    <button
        class="btn btn-secondary"
        data-request="onSyncPackages"
        data-request-message="<?= __("Syncing...") ?>">
        <i class="icon-reload"></i>
        <?= __("Sync Packages") ?>
    </button>

</div>