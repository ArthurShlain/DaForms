<?php

if(!function_exists('action_prepare')) {
    function action_prepare($data)
    {
        $data['tel'] = preg_replace('/[^0-9]/', '', $data['tel']);

        return array(
            'values' => array(
                'tel' => $data['tel']
            )
        );
    }
}
