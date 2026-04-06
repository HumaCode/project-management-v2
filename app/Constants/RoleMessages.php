<?php

namespace App\Constants;

class RoleMessages
{
    const TITLE                                 = 'Role Management';
    const SUBTITLE                              = 'Kelola data role sistem';
    const FORMVIEW                              = 'pages.role-management.roles.role-form';
    const INDEXVIEW                             = 'pages.role-management.roles.index';

    const PAGINATIONURL                         = 'roles.allPagination';
    const CREATEURL                             = 'roles.create';
    const AKSESURL                              = 'roles.akses';
    const AKSESEDITURL                          = 'roles.akses.edit';
    const EDITURL                               = 'roles.edit';
    const SHOWURL                               = 'roles.show';
    const STOREURL                              = 'roles.store';
    const UPDATEURL                             = 'roles.update';
    const DESTROYURL                            = 'roles.destroy';

    const TABLEID                               = 'table-role';
    const AKSES_PERMISSION                      = 'roles';

    const ICON                                  = 'bi bi-diagram-3-fill';


    const RETRIEVED_SUCCESS            = 'Role data berhasil diambil';
    const CREATED_SUCCESS              = 'Role berhasil ditambahkan';
    const UPDATED_SUCCESS              = 'Role berhasil diubah';
    const DELETED_SUCCESS              = 'Role berhasil dihapus';
    const AKSES_UPDATED_SUCCESS        = 'Hak akses role berhasil diperbarui';
}
