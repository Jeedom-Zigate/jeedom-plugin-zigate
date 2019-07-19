<?php
/**
 * Copyright (c) 2018 Jeedom-ZiGate contributors
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

// phpcs:disable PSR1.Files.SideEffects
require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
// phpcs:enable

/**
 * Jeedom plugin installation function.
 */
function zigate_install()
{
}

/**
 * Jeedom plugin update function.
 */
function zigate_update()
{
    log::add('zigate', 'debug', 'zigate_update');
    $daemonInfo = zigate::deamon_info();

    if ($daemonInfo['state'] == 'ok') {
        zigate::deamon_stop();
    }

    $cache = cache::byKey('dependancy' . 'zigate');
    $cache->remove();

    zigate::dependancy_install();

    message::removeAll('Zigate');
    message::add('Zigate', '{{Mise à jour du plugin Zigate terminée, vous êtes en version }}' . zigate::getVersion() . '.', null, null);

    zigate::deamon_start();
}

/**
 * Jeedom plugin remove function.
 */
function zigate_remove()
{
}
