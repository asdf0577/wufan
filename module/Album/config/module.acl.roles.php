<?php
   return array(
       'roles' => array(
                'guest',
                'student',
                'teacher',
                'admin',
        ),
       'permissions' => array(
           'student' => array(
               'feeds',
               'feeds-subscribe',
               'feeds-unsubscribe'
),

       )
);