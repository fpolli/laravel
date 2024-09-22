<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    //
    DB::table('sites')->insert([
      [
        'owner_id' => 0,
        'code' => 'moms',
        'name' => 'Man of Mystery Studios',
        'domain' => 'manofmysterystudios.com',
        'local_url' => 'manofmystery.test',
        'development_url' => 'development.manofmysterystudios.com',
        'description' => 'Multimedia and interactive publications with a socialist perspective',
        'site_password' => 'kjgfkjTFI&^%$Rrfirfkytfi657r6986rfoir7%^R(IFI',
        'genres' => json_encode([
          'article' => 'text',
          'podcast' => 'audio',
          'song' => 'audio',
          'movie' => 'video',
          'video' => 'video'
        ]),
        'volumes' => json_encode([
          'season'  => [
            'genres' => ['podcast'],
            'items' => [
              'episode' => [
                'genres' => ['podcast'],
                'indexStyle' => '1'
              ]
            ],
            'groups' => [
              [
                'season' => [
                  'indexStyle' => 'One',
                  'flags'  => [
                    'showIndex' => true,
                    'showTitle' => true,
                    'showBlurb' => true,
                    'newPage' => true,
                    'restartNumbers' => true
                  ],
                  'items' => [
                    'required' => ['episode'],
                    'allowed' => []
                  ],
                  'groups' => [
                    'required' => [],
                    'allowed' => []
                  ]
                ]
              ]
            ]
          ],
          'show'  => [
            'genres' => ['season'],
            'items' => [
              'season' => [
                'genres' => ['season'],
                'indexStyle' => 'One'
              ]
            ],
            'groups' => [
              [
                'show' => [
                  'indexStyle' => 'None',
                  'flags'  => [
                    'showIndex' => false,
                    'showTitle' => true,
                    'showBlurb' => true,
                    'newPage' => true,
                    'restartNumbers' => true
                  ],
                  'items' => [
                    'required' => ['season'],
                    'allowed' => []
                  ],
                  'groups' => [
                    'required' => [],
                    'allowed' => []
                  ]
                ]
              ]
            ]
          ],
          'album'  => [
            'genres' => ['song'],
            'items' => [
              'track' => [
                'genres' => ['song'],
                'indexStyle' => '1'
              ]
            ],
            'groups' => [
              [
                'album' => [
                  'indexStyle' => 'None',
                  'flags'  => [
                    'showIndex' => false,
                    'showTitle' => true,
                    'showBlurb' => true,
                    'newPage' => true,
                    'restartNumbers' => true
                  ],
                  'items' => [
                    'required' => ['track'],
                    'allowed' => []
                  ],
                  'groups' => [
                    'required' => [],
                    'allowed' => []
                  ]
                ]
              ]
            ]
          ],
          'soundtrack'  => [
            'genres' => ['song'],
            'items' => [
              'track' => [
                'genres' => ['song'],
                'indexStyle' => '1'
              ]
            ],
            'groups' => [
              [
                'soundtrack' => [
                  'indexStyle' => 'None',
                  'flags'  => [
                    'showIndex' => false,
                    'showTitle' => true,
                    'showBlurb' => true,
                    'newPage' => true,
                    'restartNumbers' => true
                  ],
                  'items' => [
                    'required' => ['track'],
                    'allowed' => []
                  ],
                  'groups' => [
                    'required' => [],
                    'allowed' => []
                  ]
                ]
              ]
            ]
          ],
          'trilogy' => [
            'genres' => ['movie'],
            'items' => [
              'episode' => [
                'genres' => ['movie'],
                'indexStyle' => '1'
              ]
            ],
            'groups' => [
              [
                'trilogy' => [
                  'indexStyle' => 'One',
                  'flags'  => [
                    'showIndex' => true,
                    'showTitle' => true,
                    'showBlurb' => true,
                    'newPage' => true,
                    'restartNumbers' => true
                  ],
                  'items' => [
                    'required' => ['episode'],
                    'allowed' => []
                  ],
                  'groups' => [
                    'required' => [],
                    'allowed' => []
                  ]
                ]
              ]
            ]
          ],
          'franchise' => [
            'genres' => ['movie'],
            'items' => [
              'episode' => [
                'genres' => ['movie'],
                'indexStyle' => 'None'
              ]
            ],
            'groups' => [
              [
                'franchise' => [
                  'indexStyle' => 'None',
                  'flags'  => [
                    'showIndex' => false,
                    'showTitle' => true,
                    'showBlurb' => true,
                    'newPage' => true,
                    'restartNumbers' => true
                  ],
                  'items' => [
                    'required' => ['episode'],
                    'allowed' => []
                  ],
                  'groups' => [
                    'required' => [],
                    'allowed' => []
                  ]
                ]
              ]
            ]
          ]
        ]),
        'media' => json_encode([
          'shows'  => ['article', 'podcast', 'season', 'show'],
          'movies'  => ['article', 'movie', 'trilogy', 'franchise'],
          'music'  => ['article', 'song', 'video', 'album', 'soundtrack'],
          'games'  => ['article', 'trilogy', 'franchise']
        ]),
        'mail_server' => json_encode(
          [
            'mail.mailers.smtp.username' => 'admin@manofmysterystudios.com',
            'mail.mailers.smtp.password' => 'R1N#RBj0TkC!&SW@',
            'mail.from.address' => 'admin@manofmysterystudios.com',
            'mail.from.name' => 'Man of Mystery Studios Admin',
          ]
        ),
        'public' => true
      ],
      [
        'owner_id' => 0,
        'code' => 'cowp',
        'name' => 'Children of Wonder Publishing',
        'domain' => 'childrenofwonder.com',
        'local_url' => 'children.test',
        'development_url' => 'development.childrenofwonder.com',
        'description' => 'Books, periodicals and courses with a socialist perspective',
        'site_password' => '',
        'mail_server' => json_encode(
          [
            'mail.mailers.smtp.username' => 'admin@childrenofwonder.com',
            'mail.mailers.smtp.password' => '',
            'mail.from.address' => 'admin@childrenofwonder.com',
            'mail.from.name' => 'Children of Wonder Publishing Admin',
          ]
        ),
        'public' => true
      ],
      [
        'owner_id' => 0,
        'code' => 'sobb',
        'name' => 'Seeds of the Burning Bush LLC',
        'domain' => 'seedsoftheburningbushllc.com',
        'local_url' => 'seeds.test',
        'development_url' => 'development.seedsoftheburningbushllc.com',
        'description' => 'A community of accountability and mutual support',
        'site_password' => '',
        'mail_server' => json_encode(
          [
            'mail.mailers.smtp.username' => 'admin@seedsoftheburningbushllc.com',
            'mail.mailers.smtp.password' => '7$^VaC#jujVSXA38',
            'mail.from.address' => 'admin@seedsoftheburningbushllc.com',
            'mail.from.name' => 'Seeds of the Burning Bush LLC Admin',
          ]
        ),
        'public' => true
      ],
    ]);
  }
}
