<?php
enum Role: string
{
    case ADMIN = 'admin';
    case TECHNICIAN = 'technician';
    case TEACHER = 'teacher';
    case STUDENT = 'student';
}