<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace Netzmacht\Bootstrap\Form\Subscriber;

use Netzmacht\Bootstrap\Core\Config;
use Netzmacht\Bootstrap\Core\Config\ContextualConfig;
use Netzmacht\Bootstrap\Core\Util\AssetsManager;
use Netzmacht\Bootstrap\Form\InputGroup;
use Netzmacht\Contao\FormHelper\Event\ViewEvent;
use Netzmacht\Contao\FormHelper\Partial\Container;
use Netzmacht\Html\Element;

/**
 * The ElementStyler creates styled form element replacements.
 *
 * @package Netzmacht\Bootstrap\Form\Subscriber
 */
class ElementStyler extends AbstractSubscriber
{
    /**
     * Bootstrap-Select localization mapping.
     *
     * @var array
     */
    private static $selectLocalizations = array(
        'cs' => 'cs_CZ',
        'de' => 'de_DE',
        'en' => 'eu_US',
        'es' => 'es_CL',
        'eu' => 'eu',
        'fr' => 'fr_FR',
        'it' => 'it_IT',
        'nl' => 'nl_NL',
        'pl' => 'pl_PL',
        'bt' => 'pt_BR',
        'ro' => 'ro_RO',
        'ru' => 'ru_RU',
        'ua' => 'ua_UA',
        'zh' => 'zh_CN',
        // Not supported zh_TW
    );

    /**
     * Register styled select assets.
     *
     * @param Config|ContextualConfig $config The bootstrap config.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    private function registerStyledSelectAssets($config)
    {
        $javascripts = (array) $config->get('form.styled-select.javascript');
        $stylesheets = $config->get('form.styled-select.stylesheet');
        $language    = substr($GLOBALS['TL_LANGUAGE'], 0, 2);

        if (isset(static::$selectLocalizations[$language])) {
            $javascripts[] = 'system/modules/bootstrap-form/assets/bootstrap-select/js/i18n/defaults-' .
                static::$selectLocalizations[$language] . '.min.js';
        }

        AssetsManager::addJavascripts($javascripts, 'bootstrap-styled-select');
        AssetsManager::addStylesheets($stylesheets, 'bootstrap-styled-select');
    }

    /**
     * Set bootstrap select element attributes..
     *
     * @param ContextualConfig $config  The config.
     * @param Element          $element The select element.
     * @param \Widget          $widget  The widget.
     *
     * @return void
     */
    private function setStyledSelectAttributes(ContextualConfig $config, $element, $widget)
    {
        $element->addClass($config->get('form.styled-select.class'));
        $element->setAttribute('data-style', $config->get('form.styled-select.style'));

        // If a btn-* class isset, set it as data-style attribute.
        $classes = explode(' ', $widget->class);
        foreach ($classes as $class) {
            if (strpos($class, 'btn-') === 0) {
                $element->removeClass($class);
                $element->setAttribute('data-style', $class);
                break;
            }
        }
    }

    /**
     * Generate the upload field.
     *
     * @param ContextualConfig $config    The bootstrap config.
     * @param Container        $container Form element container.
     * @param \Widget          $widget    Form widget.
     *
     * @return void
     */
    private function generateUpload(ContextualConfig $config, Container $container, $widget)
    {
        $config  = $config->get('form.styled-upload');
        $element = $container->getElement();

        /** @var Element $element */
        $element->addClass('sr-only');
        $element->setAttribute('onchange', sprintf($config['onchange'], $element->getId()));

        $input = Element::create('input', array('type' => 'text'))
            ->setId($element->getId() . '_value')
            ->addClass('form-control')
            ->setAttribute('disabled', true)
            ->setAttribute('name', $element->getAttribute('name') . '_value');

        if ($element->hasAttribute('placeholder')) {
            $input->setAttribute('placeholder', $element->getAttribute('placeholder'));
        } elseif ($widget->placeholder) {
            $input->setAttribute('placeholder', $widget->placeholder);
        }

        $click  = sprintf('$(%s).click();return false;', $element->getId());
        $submit = Element::create('button', array('type' => 'submit'))
            ->addChild($config['label'])
            ->addClass($config['class'])
            ->setAttribute('onclick', $click);

        $inputGroup = new InputGroup();
        $inputGroup->setElement($input);

        if ($config['position'] == 'left') {
            $inputGroup->setLeft($submit, $inputGroup::BUTTON);
        } else {
            $inputGroup->setRight($submit, $inputGroup::BUTTON);
        }

        $container->addChild('upload', $inputGroup);
    }

    /**
     * Handle the event to style the widgets.
     *
     * @param ViewEvent $event The subscribed events.
     *
     * @return void
     */
    public function handle(ViewEvent $event)
    {
        $container = $event->getContainer();
        $element   = $container->getElement();

        if (!$element instanceof Element) {
            return;
        }

        $config = static::getConfig($event->getFormModel());
        $widget = $event->getWidget();

        // Create styled select field.
        if ($config->get('form.styled-select.enabled')
            && static::getWidgetConfigValue($config, $widget->type, 'styled-select')) {

            $this->registerStyledSelectAssets($config);
            $this->setStyledSelectAttributes($config, $element, $widget);
        }

        // Create styled upload field.
        if ($event->getWidget()->type == 'upload' && $config->get('form.styled-upload.enabled')) {
            $this->generateUpload($config, $container, $widget);
        }
    }
}