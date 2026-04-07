<?php

namespace App\Constants\RoleManagement;

class PermissionMessages
{
    const TITLE                                 = 'Permission Management';
    const SUBTITLE                              = 'Kelola data permission sistem';
    const FORMVIEW                              = 'pages.role-management.permissions.permission-form';
    const INDEXVIEW                             = 'pages.role-management.permissions.index';

    const PAGINATIONURL                         = 'permissions.allPagination';
    const CREATEURL                             = 'permissions.create';
    const AKSESURL                              = 'permissions.akses';
    const AKSESEDITURL                          = 'permissions.akses.edit';
    const EDITURL                               = 'permissions.edit';
    const SHOWURL                               = 'permissions.show';
    const STOREURL                              = 'permissions.store';
    const UPDATEURL                             = 'permissions.update';
    const DESTROYURL                            = 'permissions.destroy';

    const TABLEID                               = 'table-permission';
    const AKSES_PERMISSION                      = 'permissions';

    const ICON                                  = 'bi bi-shield-fill-check';


    const RETRIEVED_SUCCESS            = 'Permission data berhasil diambil';
    const CREATED_SUCCESS              = 'Permission berhasil ditambahkan';
    const UPDATED_SUCCESS              = 'Permission berhasil diubah';
    const DELETED_SUCCESS              = 'Permission berhasil dihapus';
}
