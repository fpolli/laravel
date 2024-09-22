<?php

namespace Database\Seeders;

use App\FredOS\Services\Market\ProductService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Market\Product;

class ProductSeeder extends Seeder
{
  protected $productner;

  public function __construct()
  {
    $this->productner = new ProductService();
  }
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    //
    $momsFriend = $this->productner->createProduct([
      'name' => 'Friend',
      'site' => 'moms',
      'type' => 'plan',
      'active' => true,
      'public' => true,
      'stripe_product_id' => 'prod_NyLfwTrBQxo7Ib',
      'description' => "Support the site and help keep the content coming with your contribution. You will enjoy all the basic content and receive discounts on select Premium Works",
      'specifications' => [
        'Content' => [
          'All Basic content on this site',
          'Full-length Shows',
          '1/2 price downloads from this site',
        ],
        'Games' => 'One active game on our server',
        'Perks' => [
          'Can leave reviews on this site',
          'Subscribe to RSS feed or email newsletter on all sites',
        ]
      ],
      'role' => 'Friend',
      'annual' => 'price_1NCOyPGXVdLqaGKl3IjdBqdK',
      'monthly' => 'price_1NCOyPGXVdLqaGKlca638n3f'
    ]);

    $momsPatron = $this->productner->createProduct([
      'name' => 'Patron',
      'site' => 'moms',
      'type' => 'plan',
      'active' => true,
      'public' => true,
      'stripe_product_id' => 'prod_PibBwrk4x8Ck5s',
      'description' => "Become part of the creative process as a Patron. Early access, works in progress, all Premium content on this site, plus Friend status on all other sites in the Seeds Community",
      'specifications' => [
        'Content' => [
          'All Premium content on this site',
          'Basic content on all other sites',
          '1/2 price downloads on all sites',
          'Early access',
        ],
        'Games' => ['Unlimited active games on our server', 'Eligible to be Extra in Official games'],
        'Perks' => [
          'Can leave reviews on this site',
          'Subscribe to RSS feed or email newsletter on all sites',
          'Extra in video productions',
          'Works in progress updates',
          'Creator livestreams',
        ]
      ],
      'role' => 'Patron',
      'annual' => 'price_1Ot9zRGXVdLqaGKlTMeCA7YK',
      'monthly' => 'price_1Ot9zRGXVdLqaGKlICZ9sbM0'
    ]);

    $momsMember = $this->productner->createProduct([
      'name' => 'Member',
      'site' => 'moms',
      'type' => 'plan',
      'active' => true,
      'public' => true,
      'stripe_product_id' => 'prod_PibD0EvluRIWxG',
      'description' => "Become a full Member of the Seeds Community, with full access to all content on all sites, the chance to actively participate in content creation, and more!",
      'specifications' => [
        'Content' => [
          'All Premium content on every Seeds site',
          'Free downloads on all sites',
          'Patron-level access to all Seeds sites',
          "'Behind-the-scenes' content",
        ],
        'Games' => [
          'Unlimited active games on any Seeds server',
          'Eligible to be a PC in Official games',
        ],
        'Rights' => [
          'All of the Patron-level perks',
          "Eligible to be a 'Beta' consumer of Seeds content",
          'Open a Shop in the Seeds Marketplace',
          'Apply to be a Content Contributor for a Seeds site',
          'Access to robust internal messaging system',
          'Eligible to vote in community governance',
          'Eligible for leadership positions within the community',
          'Eligible to share in Seeds rewards',
        ]
      ],
      'role' => 'Member',
      'annual' => 'price_1OtA1QGXVdLqaGKlsLMBESlJ',
      'monthly' => 'price_1OtA1QGXVdLqaGKlBnT16y9d'
    ]);

    $cowpFriend = $this->productner->createProduct([
      'name' => 'Friend',
      'site' => 'cowp',
      'type' => 'plan',
      'active' => true,
      'public' => true,
      'stripe_product_id' => 'prod_NyLfwTrBQxo7Ib',
      'description' => "Support the site and help keep the content coming with your contribution. You will enjoy all the basic content and receive discounts on select Premium Works",
      'specifications' => [
        'Content' => [
          "All Basic content on this site",
          "Serialization of manuscripts",
          "1/2 price downloads from this site"
        ],
        'Perks' => [
          "Can leave reviews on this site",
          "Subscribe to RSS feed or email newsletter on all sites"
        ]
      ],
      'role' => 'Friend',
      'annual' => 'price_1NCOyPGXVdLqaGKl3IjdBqdK',
      'monthly' => 'price_1NCOyPGXVdLqaGKlca638n3f'
    ]);

    $cowpPatron = $this->productner->createProduct([
      'name' => 'Patron',
      'site' => 'cowp',
      'type' => 'plan',
      'active' => true,
      'public' => true,
      'stripe_product_id' => 'prod_PibBwrk4x8Ck5s',
      'description' => "Become part of the creative process as a Patron. Early access, works in progress, all Premium content on this site, plus Friend status on all other sites in the Seeds Community",
      'specifications' => [
        'Content' => [
          'All Premium content on this site',
          'Basic content on all other sites',
          '1/2 price downloads on all sites',
          'Early access',
        ],
        'Games' => [
          'Free downloads of electronic materials',
          '10% off tabletop games and accessories',
        ],
        'Perks' => [
          'Can leave reviews on this site',
          'Subscribe to RSS feed or email newsletter on all sites',
          'Works in progress updates',
          'Creator livestreams',
        ]
      ],
      'role' => 'Patron',
      'annual' => 'price_1Ot9zRGXVdLqaGKlTMeCA7YK',
      'monthly' => 'price_1Ot9zRGXVdLqaGKlICZ9sbM0'
    ]);

    $cowpMember = $this->productner->createProduct([
      'name' => 'Member',
      'site' => 'cowp',
      'type' => 'plan',
      'active' => true,
      'public' => true,
      'stripe_product_id' => 'prod_PibD0EvluRIWxG',
      'description' => "Become a full Member of the Seeds Community, with full access to all content on all sites, the chance to actively participate in content creation, and more!",
      'specifications' => [
        'Content' => [
          'All Premium content on every Seeds site',
          'Free downloads on all sites',
          'Patron-level access to all Seeds sites',
          "'Behind-the-scenes' content",
        ],
        'Games' => [
          'Unlimited active games on any Seeds server',
          'Eligible to be a PC in Official games',
        ],
        'Rights' => [
          'All of the Patron-level perks',
          "Eligible to be a 'Beta' consumer of Seeds content",
          'Open a Shop in the Seeds Marketplace',
          'Apply to be a Content Contributor for a Seeds site',
          'Access to robust internal messaging system',
          'Eligible to vote in community governance',
          'Eligible for leadership positions within the community',
          'Eligible to share in Seeds rewards',
        ]
      ],
      'role' => 'Member',
      'annual' => 'price_1OtA1QGXVdLqaGKlsLMBESlJ',
      'monthly' => 'price_1OtA1QGXVdLqaGKlBnT16y9d'
    ]);
  }
}
