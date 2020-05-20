<?php

/*
 | Platform Configuration
 |
 | @version 1.0.0
 | @author Nick Verheijen <verheijen.webdevelopment@gmail.com>
*/

return [

    /**
     * Available locales
     * 
     * This array will be used to populate the LocaleSwitcher component in the app layout.
     */
    "locales" => ["nl", "en"],

    /**
     * Reputation points
     * 
     * Configure how many points are awarded throughout the application
     */
    "reputation" => [

        // Smallest possible amount of reputation points one can receive
        "base_points" => 10,
        
    ],

    /**
     * Feedback
     * 
     * Configure where emails are sent to when somebody submits feedback
     */
    "feedback" => [

        // General feedback settings
        "general" => [

            // Should we send emails to someone when we receive general feedback?
            "emails_enabled" => true,

            // The email addresses to mail the feedback reports to
            "emails" => [
                "verheijen.webdevelopment@gmail.com",
            ],

        ],

        // Gebruikerspanel options
        "gebruikerspanel" => [

            // Should we send gebruikerspanel specific feedback to someone?
            "emails_enabled" => true,

            // Emails to send the feedback reports to
            "emails" => [
                "verheijen.webdevelopment@gmail.com",
            ],

        ],

    ],

];
