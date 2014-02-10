<?php

    namespace Matheos\App;

    class ApplePushNotification {
        private $devices, $sslPass, $sslCert, $ctx;

        function __construct() {
            $AppConfig = \Matheos\MicroMVC\AppConfig::getInstance()->config;
            $this->devices = array(
                // Device Tokens
            );
            $this->sslPass = "password";
            $this->sslCert = $AppConfig->Core->rootFolder . "/cert.pem";
            $this->ctx     = stream_context_create();
        }

        public function sendNotification($message) {
            stream_context_set_option($this->ctx, 'ssl', 'local_cert', $this->sslCert);
            stream_context_set_option($this->ctx, 'ssl', 'passphrase', $this->sslPass);

            // Open a connection to the APNS server
            $fp = stream_socket_client(
                'ssl://gateway.sandbox.push.apple.com:2195', $err,
                $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $this->ctx
            );

            if (!$fp) {
                throw new \Exception("APNS: Failed to connect: $err $errstr");
            }

            // Create the payload body
            $body['aps'] = array(
                'alert' => $message,
                'badge' => 0,
                'sound' => 'default'
            );

            // Encode the payload as JSON
            $payload = json_encode($body);

            foreach($this->devices as $device) {
                // Build the binary notification
                $msg = chr(0) . pack('n', 32) . pack('H*', $device) . pack('n', strlen($payload)) . $payload;
                // Send it to the server
                $result = fwrite($fp, $msg, strlen($msg));

                if (!$result)
                    echo 'Message not delivered' . PHP_EOL;
            }

            // Close the connection to the server
            fclose($fp);
        }
    }
