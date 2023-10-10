<?php

namespace backend\enums;

enum RequestStatusEnum: int
{
    case New = 0;
    case InWork = 1;
    case Resolved = 2;
}