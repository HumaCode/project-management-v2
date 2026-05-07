<?php

namespace App\Constants\Project;

class ProjectMessages
{
    const TITLE                                 = 'Project';
    const SUBTITLE                              = 'Kelola semua project & anggota tim';
    const INDEXVIEW                             = 'pages.project.index';
    const CREATEVIEW                            = 'pages.project.create';
    const FORMVIEW                              = 'pages.project.project-form';

    const PAGINATIONURL                         = 'projects.allPagination';
    const CREATEURL                             = 'projects.create';
    const EDITURL                               = 'projects.edit';
    const SHOWURL                               = 'projects.show';
    const STOREURL                              = 'projects.store';
    const UPDATEURL                             = 'projects.update';
    const DESTROYURL                            = 'projects.destroy';

    const TABLEID                               = 'table-project';
    const AKSES_PERMISSION                      = 'projects';

    const ICON                                  = 'bi bi-kanban-fill';

    const RETRIEVED_SUCCESS            = 'Data project berhasil diambil';
    const CREATED_SUCCESS              = 'Project berhasil ditambahkan';
    const UPDATED_SUCCESS              = 'Project berhasil diubah';
    const DELETED_SUCCESS              = 'Project berhasil dihapus';
}
