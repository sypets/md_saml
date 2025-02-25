<?php
defined('TYPO3') || die();

$settings = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('md_saml') ?? [];

$beActive = (bool)($settings['be_users']['active'] ?? true);
$feActive = (bool)($settings['fe_users']['active'] ?? true);

$subtypes = [];

if ($beActive) {
    $subtypes[] = 'authUserBE';
    $subtypes[] = 'getUserBE';
}

if ($feActive) {
    $subtypes[] = 'authUserFE';
    $subtypes[] = 'getUserFE';
}

if ($subtypes) {

    /**
     * Register the auth service
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
        'md_saml',
        'auth',
        \Mediadreams\MdSaml\Authentication\SamlAuthService::class,
        [
            'title' => 'BE ADFS Authentication',
            'description' => 'Authentication with a Microsoft ADFS',
            //'subtype' => 'authUserFE,getUserFE,authUserBE,getUserBE',
            'subtype' => implode(',', $subtypes),
            'available' => true,
            'priority' => 80,
            'quality' => 80,
            'os' => '',
            'exec' => '',
            'className' => \Mediadreams\MdSaml\Authentication\SamlAuthService::class,
        ]
    );
}

if ($beActive) {
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['backend']['loginProviders'][1648123062] = [
        'provider' => \Mediadreams\MdSaml\LoginProvider\SamlLoginProvider::class,
        'sorting' => 50,
        'iconIdentifier' => 'actions-key',
        'label' => 'LLL:EXT:md_saml/Resources/Private/Language/locallang.xlf:login.md_saml',
    ];
}
