<?php

/**
 * Class NotificationsHandler
 * TODO: Create Notification functions manually as per requirement
 */

class NotificationsHandler
{

    public static function saveAndContinue($token,$email,$additional=[]){

        if(!empty($email)){
            $subject = "Information saved";
            $link = $token;
            $assignNotification = (get_option("assignedNotifications")) ? get_option("assignedNotifications") : [];
            $emailContent = $link;
            if(!empty($assignNotification)){
                $templates = (get_option("templateList")) ? get_option("templateList") : [];

                $emailContent = (!empty($templates[$assignNotification['snc']])) ? html_entity_decode(($templates[$assignNotification['snc']]['emailContent'])) : $link;
                $subject = (!empty($templates[$assignNotification['snc']])) ? $templates[$assignNotification['snc']]['emailSubject'] : $link;


                $replacement['studentName'] = $additional['name'];
                $replacement['saveAndContinueLink'] = $additional['link'];
                foreach ($replacement as $key=>$value){
                    $emailContent = str_replace("{".$key."}",$value,$emailContent);
                }

            }
            $headers = array('Content-Type: text/html; charset=UTF-8');

            if(wp_mail($email,$subject,$emailContent,$headers)){
                return true;
            }else{
                return false;
            }
        }

        return false;
    }

}