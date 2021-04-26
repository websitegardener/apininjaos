<?php

function epxhydro_fs()
{
    global  $epxhydro_fs ;
    if ( !isset( $epxhydro_fs ) ) {
        $epxhydro_fs = fs_dynamic_init( array(
            'id'               => '5819',
            'slug'             => 'erropix-hydrogen-pack',
            'premium_slug'     => 'erropix-hydrogen-pack',
            'type'             => 'plugin',
            'public_key'       => 'pk_badc15f654054417bbd616deafffc',
            'is_premium'       => true,
            'is_premium_only'  => true,
            'has_addons'       => false,
            'has_paid_plans'   => true,
            'is_org_compliant' => false,
            'trial'            => array(
            'days'               => 7,
            'is_require_payment' => true,
        ),
            'has_affiliation'  => 'selected',
            'menu'             => array(
            'slug'        => 'hydrogen-pack',
            'parent'      => array(
            'slug' => 'ct_dashboard_page',
        ),
            'account'     => false,
            'affiliation' => false,
            'contact'     => false,
            'support'     => false,
        ),
            'is_live'          => true,
        ) );
    }
    return $epxhydro_fs;
}
