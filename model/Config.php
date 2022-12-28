<?php
namespace Model;

class Config {
    public function getUserTypeIDToUserTypeMap() {
        $userTypeIDToUserTypeMap = [
            1 => 'teacher',
            2 => 'student',
            3 => 'admin'
        ];

        return $userTypeIDToUserTypeMap;
    }
}
?>
