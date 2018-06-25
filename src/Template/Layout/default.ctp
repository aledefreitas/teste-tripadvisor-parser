<?php
/**
 * TESTE EPICS - TripAdvisor Parser
 *
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Routing\Router;
?>
<!DOCTYPE html>
<html>
<head>
    <?php echo $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Teste EPICS
    </title>
    <?php echo $this->Html->css($this->render_asset('style.css')) ?>
</head>
<body>
    <?php echo $this->element('topnav') ?>

    <?php echo $this->Flash->render() ?>
    <div class="container clear-fix">
        <?= $this->fetch('content') ?>
    </div>

    <script>
        window.WS_URL = 'ws://192.168.1.200:3005/';
    </script>

    <?php echo $this->Html->script($this->render_asset('custom.js') . "?session_id=".$session_id) ?>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.js"></script>
</body>
</html>
