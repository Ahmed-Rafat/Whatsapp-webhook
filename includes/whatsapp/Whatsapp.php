<?php

class Whatsapp {

    private $response;

    public $data;

    public function __construct( $response ) {
        $this->response = $response;
    }

    /**
    * Get all response from telegram
    */

    public function getResponse() {
        return $this->response;
    }

    public function senderName() {
        return $this->messageData( 'pushName' );
    }

    public function number() {
        return str_replace( '@s.whatsapp.net', '', $this->messageData( 'key.remoteJid' ) );
    }

    public function isFromMe() {
        return $this->messageData( 'key.fromMe' ) ?: false;
    }

    public function message() {
        return $this->messageData( 'message.conversation' );
    }

    public function messageData( $key, $default = null ) {
        return getNestedVar( $this->get( 'data.data.messages' )[ 0 ] ?? [], $key ) ?? $default;
    }

    public function sendAt() {
        return date( 'Y-m-d H:i:s', $this->get( 'message.date' ) );
    }

    public function isValidMessage() {
        return strpos( $this->message(), '#' ) === 0;
    }

    /**
    * @param string $key
    * @param mixed|null $default
    * @return mixed|null
    */

    public function get( $key, $default = null ) {
        return getNestedVar( $this->response, $key ) ?? $default;
    }

    /**
    * Sends a text message via WhatsApp using a POST request.
    *
    * @param string $message The message to send.
    * @param string|null $number The recipient's phone number. If null, the default number is used.
    * @return bool True if the message was sent successfully, false otherwise.
    */
    public function sendMessage($message, $number = null) {
        $number = $number ?: $this->number();

        // Prepare the POST data
        $postData = array(
            'number' => $number,
            'type' => 'text',
            'message' => $message,
            'instance_id' => WHATSAPP_INSTANCE_ID,
            'access_token' => WHATSAPP_ACCESS_TOKEN
        );

        // Initialize cURL session
        $ch = curl_init(WHATSAPP_URL);

        // Set cURL options for a POST request
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for errors
        if ($response === false) {
            // You can handle the error here
            return false;
        }

        // Close the cURL session
        curl_close($ch);

        // Process the response if needed
        // You might want to check the response for success or error messages here

        return true;
    }

    /**
     * Sends a file message via WhatsApp using a POST request.
     *
     * @param string $file The URL of the media file to send.
     * @param string $message Optional message to include with the file.
     * @param string|null $number The recipient's phone number. If null, the default number is used.
    * @return bool True if the file was sent successfully, false otherwise.
    */

    public function sendFile( $file, $message = '', $number = null ) {
        $number = $number ?: $this->number();

        // Prepare the POST data
        $postData = array(
            'number' => $number,
            'type' => 'media',
            'media_url' => $file,
            'message' => $message,
            'instance_id' => WHATSAPP_INSTANCE_ID,
            'access_token' => WHATSAPP_ACCESS_TOKEN
        );

        // Initialize cURL session
        $ch = curl_init( WHATSAPP_URL );

        // Set cURL options for a POST request
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $postData ) );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        // Execute the cURL request
        $response = curl_exec( $ch );

        // Check for errors
        if ( $response === false ) {
            // You can handle the error here
            return false;
        }

        // Close the cURL session
        curl_close( $ch );

        // Process the response if needed
        // You might want to check the response for success or error messages here

        return true;
    }
}