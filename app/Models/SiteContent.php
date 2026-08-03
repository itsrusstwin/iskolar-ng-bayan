<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Editable site-wide content blocks for the public pages
 * (About Us, Application Guide). Each row is one editable block,
 * keyed by a stable string identifier.
 */
class SiteContent extends Model
{
    protected $fillable = [
        'key',
        'content',
    ];

    public static function defaults(): array
    {
        return [
            'about_hero_badge' => 'Who we are',
            'about_hero_title' => 'About Us',
            'about_hero_subtitle' => 'Iskolar ng Bayan is the scholarship program of the Municipality of Santa Cruz, Laguna, supporting local youth in their pursuit of higher education.',
            'about_mission_title' => 'Our Mission',
            'about_mission' => 'Information about the Iskolar ng Bayan scholarship program goes here.',
            'about_partner_label' => 'Program handled by',

            'guides_hero_badge' => 'Step-by-step',
            'guides_hero_title' => 'Application Guide',
            'guides_hero_subtitle' => 'Follow these four steps to complete your scholarship application.',
            'guides_steps' => json_encode([
                [
                    'en' => 'Register on the iskolar.ng.bayan website. Click Login/Sign up above and it will take you to the login page — then click Create new account. If you already have an account, just log in.',
                    'fil' => 'Magrehistro sa website na iskolar.ng.bayan. I-click ang Login/Sign up sa itaas, at dadalhin ka nito sa login page. Pagkatapos, i-click ang Create new account kung wala ka pang account. Kung mayroon ka nang account, mag-login na lamang.',
                ],
                [
                    'en' => 'Fill up the scholar profile and upload the CERTIFIED TRUE COPIES of the required documents as a PDF file.',
                    'fil' => 'Magfill-up ng scholar profile at i-upload ang mga CERTIFIED TRUE COPIES ng mga kinakailangang dokumento na naka-PDF.',
                ],
                [
                    'en' => 'Wait for the verification of your submitted requirements. You will be notified through your account status if you qualify to proceed to the next step.',
                    'fil' => 'Hintayin ang beripikasyon ng iyong mga isinumiteng requirements. Ikaw ay mapapabatid sa pamamagitan ng iyong account status kung ikaw ay kwalipikado upang magpatuloy sa susunod na hakbang.',
                ],
                [
                    'en' => 'Once qualified, take the scholarship exam on the scheduled date. Results will be posted on your account.',
                    'fil' => 'Kapag kwalipikado, kunin ang scholarship exam sa naka-iskedyul na petsa. Ang mga resulta ay ipo-post sa iyong account.',
                ],
            ]),
        ];
    }

    /**
     * Return all editable content as a keyed array, merged over defaults.
     */
    public static function allContent(): array
    {
        $values = static::defaults();

        foreach (static::all(['key', 'content']) as $row) {
            if (array_key_exists($row->key, $values)) {
                $values[$row->key] = $row->content;
            }
        }

        return $values;
    }
}
