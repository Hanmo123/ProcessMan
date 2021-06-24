<?php
while(1){
    echo mt_rand();
    sleep(1);
    echo fgets(STDIN);
}