<?php

namespace backend\enums;

enum UserRoleEnum: int
{
    case User = 0;
    case Manager = 1;
    case Admin = 2;
}