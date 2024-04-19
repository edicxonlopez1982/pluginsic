<?php
class block_last_activity_users extends block_base {
    
    public function init() {
        $this->title = get_string('pluginname', 'block_last_activity_users');
    }

    public function get_content() {
        global $DB;

        if ($this->content !== null) {
            return $this->content;
        }

        $content = new stdClass();

        // Obtener los últimos 5 usuarios activos
        $sql = "
        SELECT DISTINCT u.firstname, u.lastname FROM mdl3b_user_enrolments ue JOIN mdl3b_user u ON ue.userid = u.id ORDER BY u.lastaccess DESC LIMIT 5;
            
        ";
        $params = ['time' => strtotime('-1 week')];
        $users = $DB->get_records_sql($sql, $params);

        if (!empty($users)) {
            $list = '<ul>';
            foreach ($users as $user) {
                $list .= "<li>{$user->firstname} {$user->lastname}</li>";
            }
            $list .= '</ul>';
            $content->text = $list;
        } else {
            $content->text = get_string('noactivity', 'block_last_activity_users');
        }

        $this->content = new stdClass();
        $this->content->text = $content->text;
        return $this->content;
    }

    public function specialization() {
        $this->title = get_string('pluginname', 'block_last_activity_users');
    }

    public function applicable_formats() {
        return array('all' => true);
    }
}
