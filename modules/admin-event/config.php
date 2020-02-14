<?php

return [
    '__name' => 'admin-event',
    '__version' => '0.1.0',
    '__git' => 'git@github.com:getmim/admin-event.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'http://iqbalfn.com/'
    ],
    '__files' => [
        'modules/admin-event' => ['install','update','remove'],
        'theme/admin/event' => ['install','update','remove']
    ],
    '__dependencies' => [
        'required' => [
            [
                'admin' => NULL
            ],
            [
                'lib-formatter' => NULL
            ],
            [
                'lib-form' => NULL
            ],
            [
                'lib-pagination' => NULL
            ],
            [
                'event' => NULL
            ],
            [
                'lib-upload' => NULL
            ],
            [
                'admin-site-meta' => NULL
            ]
        ],
        'optional' => []
    ],
    'autoload' => [
        'classes' => [
            'AdminEvent\\Controller' => [
                'type' => 'file',
                'base' => 'modules/admin-event/controller'
            ],
            'AdminEvent\\Library' => [
                'type' => 'file',
                'base' => 'modules/admin-event/library'
            ]
        ],
        'files' => []
    ],
    'routes' => [
        'admin' => [
            'adminEvent' => [
                'path' => [
                    'value' => '/event'
                ],
                'method' => 'GET',
                'handler' => 'AdminEvent\\Controller\\Event::index'
            ],
            'adminEventEdit' => [
                'path' => [
                    'value' => '/event/(:id)',
                    'params' => [
                        'id' => 'number'
                    ]
                ],
                'method' => 'GET|POST',
                'handler' => 'AdminEvent\\Controller\\Event::edit'
            ],
            'adminEventRemove' => [
                'path' => [
                    'value' => '/event/(:id)/remove',
                    'params' => [
                        'id' => 'number'
                    ]
                ],
                'method' => 'GET',
                'handler' => 'AdminEvent\\Controller\\Event::remove'
            ]
        ]
    ],
    'adminUi' => [
        'sidebarMenu' => [
            'items' => [
                'event' => [
                    'label' => 'Event',
                    'icon' => '<i class="fas fa-calendar-week"></i>',
                    'priority' => 0,
                    'route' => ['adminEvent'],
                    'perms' => 'manage_event'
                ]
            ]
        ]
    ],
    'libForm' => [
        'forms' => [
            'admin.event.index' => [
                'q' => [
                    'label' => 'Search',
                    'type' => 'search',
                    'nolabel' => TRUE,
                    'rules' => []
                ]
            ],
            'admin.event.edit' => [
                '@extends' => ['std-site-meta', 'std-cover'],
                'title' => [
                    'label' => 'Title',
                    'type' => 'text',
                    'rules' => [
                        'required' => TRUE
                    ]
                ],
                'slug' => [
                    'label' => 'Slug',
                    'type' => 'text',
                    'slugof' => 'title',
                    'rules' => [
                        'required' => TRUE,
                        'empty' => FALSE,
                        'unique' => [
                            'model' => 'Event\\Model\\Event',
                            'field' => 'slug',
                            'self' => [
                                'service' => 'req.param.id',
                                'field' => 'id'
                            ]
                        ]
                    ]
                ],
                'embed' => [
                    'label' => 'Embed',
                    'type' => 'textarea',
                    'rules' => []
                ],
                'time_start' => [
                    'label' => 'Time Start',
                    'type' => 'datetime',
                    'rules' => [
                        'required' => TRUE
                    ]
                ],
                'time_end' => [
                    'label' => 'Time End',
                    'type' => 'datetime',
                    'rules' => []
                ],
                'price' => [
                    'label' => 'Price',
                    'type' => 'number',
                    'rules' => []
                ],
                'address' => [
                    'label' => 'Address',
                    'type' => 'textarea',
                    'rules' => []
                ],
                'content' => [
                    'label' => 'About',
                    'type' => 'summernote',
                    'rules' => []
                ],
                'meta-schema' => [
                    'options' => [
                        'Event' => 'Event',
                        'BusinessEvent' => 'BusinessEvent',
                        'ChildrensEvent' => 'ChildrensEvent',
                        'ComedyEvent' => 'ComedyEvent',
                        'CourseInstance' => 'CourseInstance',
                        'DanceEvent' => 'DanceEvent',
                        'DeliveryEvent' => 'DeliveryEvent',
                        'EducationEvent' => 'EducationEvent',
                        'EventSeries' => 'EventSeries',
                        'ExhibitionEvent' => 'ExhibitionEvent',
                        'Festival' => 'Festival',
                        'FoodEvent' => 'FoodEvent',
                        'LiteraryEvent' => 'LiteraryEvent',
                        'MusicEvent' => 'MusicEvent',
                        'PublicationEvent' => 'PublicationEvent',
                        'BroadcastEvent' => 'BroadcastEvent',
                        'OnDemandEvent' => 'OnDemandEvent',
                        'SaleEvent' => 'SaleEvent',
                        'ScreeningEvent' => 'ScreeningEvent',
                        'SocialEvent' => 'SocialEvent',
                        'SportsEvent' => 'SportsEvent',
                        'TheaterEvent' => 'TheaterEvent',
                        'VisualArtsEvent' => 'VisualArtsEvent'
                    ]
                ]
            ]
        ]
    ],
    'admin' => [
        'objectFilter' => [
            'handlers' => [
                'event' => 'AdminEvent\\Library\\Filter'
            ]
        ]
    ]
];
