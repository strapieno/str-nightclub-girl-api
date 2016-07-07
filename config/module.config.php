<?php
return [
    // Config of inject nightclub_id in body params
    'inject-route-params' => [
        'Strapieno\NightClubGirl\Api\V1\Rest\Controller' => [
            'nightclub_id'
        ]
    ],
    // Config of nightclub_id in route exist
    'nightclub-not-found' => [
        'api-rest/nightclub/girl'
    ],
    'service_manager' => [
        'factories' => [
            'Strapieno\Utils\Listener\ListenerManager' => 'Strapieno\Utils\Listener\ListenerManagerFactory'
        ],
        'invokables' => [
            'Strapieno\Utils\Delegator\AttachListenerDelegator' =>  'Strapieno\Utils\Delegator\AttachListenerDelegator'
        ],
        'aliases' => [
            'listenerManager' => 'Strapieno\Utils\Listener\ListenerManager'
        ],
        // Config of nightclub_id in route exist
        'delegators' => [
            'Application' => [
                'Strapieno\Utils\Delegator\AttachListenerDelegator',
            ]
        ],
    ],
    'service-listeners' => [
        'initializers' => [
            'Strapieno\NightClub\Model\NightClubModelInitializer'
        ],
        'invokables' => [
            'Strapieno\Utils\Listener\InjectRouteParamsInRequest' => 'Strapieno\Utils\Listener\InjectRouteParamsInRequest',
        ]
    ],
    'attach-listeners' => [
        'Application' => [
            'Strapieno\Utils\Listener\InjectRouteParamsInRequest'
        ],
        'Strapieno\NightClubGirl\Api\V1\Rest\Controller' => [
            'Strapieno\NightClubGirl\Api\V1\Listener\NightClubRestListener'
        ]
    ],
    'controllers' => [
        'delegators' => [
            'Strapieno\NightClubGirl\Api\V1\Rest\Controller' => [
                'Strapieno\Utils\Delegator\AttachListenerDelegator',
            ]
        ],
    ],
    'router' => [
        'routes' => [
            'api-rest' => [
                'child_routes' => [
                    'nightclub' => [
                        'child_routes' => [
                            'girl' => [
                                'type' => 'Segment',
                                'may_terminate' => true,
                                'options' => [
                                    'route' => '/girl[/:girl_id]',
                                    'defaults' => [
                                        'controller' => 'Strapieno\NightClubGirl\Api\V1\Rest\Controller'
                                    ],
                                    'constraints' => [
                                        'girl_id' => '[0-9a-f]{24}'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    'matryoshka-apigility' => [
        'matryoshka-connected' => [
            'Strapieno\NightClubGirl\Api\V1\Rest\ConnectedResource' => [
                'model' => 'Strapieno\NightClubGirl\Model\GirlModelService',
                'collection_criteria' => 'Strapieno\NightClubGirl\Model\Criteria\GirlCollectionCriteria',
                'entity_criteria' => 'Strapieno\Model\Criteria\NotIsolatedActiveRecordCriteria',
                'hydrator' => 'NightClubGirlApiHydrator'
            ]
        ]
    ],
    'zf-rest' => [
        'Strapieno\NightClubGirl\Api\V1\Rest\Controller' => [
            'service_name' => 'nightclubGirl',
            'listener' => 'Strapieno\NightClubGirl\Api\V1\Rest\ConnectedResource',
            'route_name' => 'api-rest/nightclub/girl',
            'route_identifier_name' => 'girl_id',
            'collection_name' => 'girls',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                'place_id'
            ],
            'page_size' => 10,
            'page_size_param' => 'page_size',
            'collection_class' => 'Zend\Paginator\Paginator', // FIXME function?
        ]
    ],
    'zf-content-negotiation' => [
        'accept_whitelist' => [
            'Strapieno\NightClubGirl\Api\V1\Rest\Controller' => [
                'application/hal+json',
                'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'Strapieno\NightClubGirl\Api\V1\Rest\Controller' => [
                'application/json'
            ],
        ],
    ],
    'zf-hal' => [
        // map each class (by name) to their metadata mappings
        'metadata_map' => [
            'Strapieno\NightClubGirl\Model\Entity\GirlEntity' => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api-rest/nightclub/girl',
                'route_identifier_name' => 'girl_id',
                'hydrator' => 'NightClubGirlApiHydrator',
            ],
        ],
    ],
    'zf-content-validation' => [
        'Strapieno\NightClubGirl\Api\V1\Rest\Controller' => [
            'input_filter' => 'Strapieno\NightClubGirl\Api\InputFilter\PostInputFilter',
        ]
    ],
    'strapieno_input_filter_specs' => [
        'Strapieno\NightClubGirl\Api\InputFilter\PostInputFilter' => [
            'merge' => 'Strapieno\NightClubGirl\Model\InputFilter\DefaultInputFilter',
            'give_name' => [
                'name' => 'give_name',
                'require' => true,
                'allow_empty' => false,
            ]
        ]
    ]
];
