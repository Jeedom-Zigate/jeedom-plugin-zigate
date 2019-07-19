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

if (!isConnect('admin')) {
    throw new Exception('401 - Accès non autorisé');
}
?>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="zigatepanel">
        <div class="tab-pane">
            <a class="btn btn-success" id="btn_startstop">{{Arrêter Terminal}}</a>
            <a class="btn btn-warning" id="btn-reset-output"><i class="fa fa-times"></i> {{Vider les résultats}}</a>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane active" id="zigateterminal">
        <div class="col-sm-4">
            <form class="form-horizontal">
                <fieldset>
                    <legend>{{Commande}}</legend>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">{{Commande}}</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control tooltips pluginAttr" data-l1key="zigateterminal" data-l2key="zigate_command" placeholder="0x0000"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">{{Data}}</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control tooltips pluginAttr" data-l1key="zigateterminal" data-l2key="zigate_data" placeholder="0123456789abcdef"/>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <a class="btn btn-success" id="btn_sendcommand">{{Envoyer}}</a>
                    </div>
                </fieldset>
            </form>
        </div>
        <div class="col-sm-8">
            <form class="form-horizontal">
                <fieldset>
                    <legend>{{Résultat}}</legend>
                    <div style="overflow: scroll; height: 500px;">
                        <pre id="pre_logZigateCommand" style="height: 100%;"></pre>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>

<script>
    var intervalHandle = null;
    function get_last_responses(){
        $.ajax({
            type: "POST",
            url: "plugins/zigate/core/ajax/zigate.ajax.php",
            data: {
                action: "get_last_responses",
            },
            dataType: 'json',
            async: true,
            global : false,
            error: function (request, status, error) {
                handleAjaxError(request, status, error);
            },
            success: function (data) {
                if (data.result.result.length > 1){
                    $('#pre_logZigateCommand').append(data.result.result);
                }
            }
        });
    }
    function startstopTimer(){
        if (intervalHandle){
            clearInterval(intervalHandle);
            intervalHandle = null;
            $("#btn_startstop").text("{{Démarrer Terminal}}");
        } else {
            intervalHandle = setInterval(get_last_responses, 1000);
            $("#btn_startstop").text("{{Arrêter Terminal}}");
        }
    }
    startstopTimer();
    $('#btn_sendcommand').on('click', function () {
        var args = {};
        $('.pluginAttr[data-l1key=zigateterminal]').each(function(){
            args[$(this).attr('data-l2key')] = $(this).value();
        });
        $.ajax({
            type: "POST",
            url: "plugins/zigate/core/ajax/zigate.ajax.php",
            data: {
                action: "raw_command",
                args: json_encode(args)
            },
            dataType: 'json',
            async: true,
            global : false,
            error: function (request, status, error) {
            handleAjaxError(request, status, error);
            },
            success: function (data) {
                $('#pre_logZigateCommand').append('{{Commande envoyée}}\n');
            }
        });
    });
    $('#btn_startstop').on('click', function () {
        startstopTimer();
    });
    $('#btn-reset-output').on('click',function(){
        $('#pre_logZigateCommand').empty();
    });
</script>
