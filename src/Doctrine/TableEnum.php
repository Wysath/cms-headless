<?php

namespace App\Doctrine;

enum TableEnum
{
    public const USER = 'user';
    public const CONTENT = 'content';
    public const COMMENTS = 'comments';

    public const UPLOAD = 'upload';

    public const IMPORT_CSV = 'import_csv';
}
