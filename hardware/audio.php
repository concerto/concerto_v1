<?php

require_once("dbfuncs.php");

define(LOCK_FILE, "/tmp/emergency_management_lock");
define(TEMP_WAVE, "/tmp/emergency_management.wav");
define(EMERG_TEXT, "/tmp/emergency_management.txt");
define(MP3_URL, "http://senatedev.union.rpi.edu/andrew/hardware/emergency/audio.mp3");
define(MP3_PATH, "/var/www/andrew/hardware/emergency/audio.mp3");
define(TIMEOUT, 180);
#define(EMERG_URL, "http://signage.rpi.edu/content/render?select_id=19");
define(EMERG_URL, "http://signage.union.rpi.edu/content/render/index.php?select_id=19&select=feed&format=raw&api=007");
#define(EMERG_URL, "http://senatedev.union.rpi.edu/andrew/hardware/testemerg.html");


class EmergencyData {
    private $emerg_data;

    public function __construct( ) {
        $this->emerg_data = file_get_contents(EMERG_URL);
        if ($this->emerg_data === FALSE) {
            $this->emerg_data = '';
        } else {
            $this->emerg_data = preg_replace('/<[^>]+>/', ' ', $this->emerg_data);
            $this->emerg_data = html_entity_decode($this->emerg_data);
            $this->emerg_data = trim($this->emerg_data);
        }
    }
    public function emergency_occurring( ) {
        if ($this->emerg_data == '') {
            return FALSE;
        } else {
            return TRUE;
        }
    }   
    public function emergency_text( ) {
        return $this->emerg_data;
    }
};

$emerg = new EmergencyData;
$chal = $_REQUEST["challenge_string"];

if (!$emerg->emergency_occurring( )) {
    // tell the screen to stop playing audio
    $url = "none";
    // remove MP3 file
    if (file_exists(MP3_PATH)) {
        unlink(MP3_PATH);
    }
} else {
    if (!file_exists(MP3_PATH) && !file_exists(LOCK_FILE)) {
        // create lock file
        file_put_contents(LOCK_FILE, "Locked!");
        // write emergency contents to text file
        file_put_contents(EMERG_TEXT, $emerg->emergency_text( ));
        // run swift to generate wave file
        system("/opt/swift/bin/swift -o " . TEMP_WAVE . " -f " . EMERG_TEXT);
        // run lame to convert to mp3
        system("lame -b 128 " . TEMP_WAVE . " " . MP3_PATH);
        // remove text file
        unlink(EMERG_TEXT);
        // remove temp wave file
        unlink(TEMP_WAVE);
        // remove lock file
        unlink(LOCK_FILE);
        $url = MP3_URL;
    } else if (!file_exists(MP3_PATH) && file_exists(LOCK_FILE)) {
        $url = "none"; // wait until other process finishes
    } else { // audio file exists
        # check the timestamp on the MP3_PATH and make sure it
        # isn't too stale
        $stat = stat(MP3_PATH);

        if (time() - $stat['mtime'] > TIMEOUT) {
            $url = "none";
        } else {
            $url = MP3_URL;
        }
    }
}

$resp = generate_signature($chal.$url);

print("$resp\n");
print("$url\n");

?>
