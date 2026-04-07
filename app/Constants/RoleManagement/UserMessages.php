<?php

namespace App\Constants\RoleManagement;

class UserMessages
{
    const TITLE                                 = 'User Management';
    const SUBTITLE                              = 'Kelola data user';
    const FORMVIEW                              = 'pages.role-management.users.user-form';
    const INDEXVIEW                             = 'pages.role-management.users.index';

    const PAGINATIONURL                         = 'users.allPagination';
    const CREATEURL                             = 'users.create';
    const AKSESURL                              = 'users.akses';
    const AKSESEDITURL                          = 'users.akses.edit';
    const EDITURL                               = 'users.edit';
    const SHOWURL                               = 'users.show';
    const STOREURL                              = 'users.store';
    const UPDATEURL                             = 'users.update';
    const DESTROYURL                            = 'users.destroy';

    const TABLEID                               = 'table-users';
    const AKSES_PERMISSION                      = 'users';

    const ICON                                  = 'bi bi-people-fill';


    const RETRIEVED_SUCCESS            = 'User data berhasil diambil';
    const CREATED_SUCCESS              = 'User berhasil ditambahkan';
    const UPDATED_SUCCESS              = 'User berhasil diubah';
    const DELETED_SUCCESS              = 'User berhasil dihapus';
}
