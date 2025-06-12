<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DisabilityPerson;
use App\Models\District;
use App\Models\Person;
use App\Models\USSD;
use Illuminate\Http\Request;
use SimpleXMLElement;

class USSDController extends Controller
{
    /* 
    {"post":[],
    "get":{
        "":"255",
        "":"256783204665",
        "":"20230906T00:05:48",
        "":"16939839342592573",
        "response":"true",
        "":"3"
    }}
    */
    public function index(Request $r)
    {

        header('Content-Type: application/xml');
        $raw = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : file_get_contents('php://input');
        $info['raw'] = $raw;
        $post = null;
        $ussd = new USSD();
        $TransactionId = "";
        $USSDServiceCode = "";
        $TransactionId = "";
        $MSISDN = "";
        $TransactionTime = "";
        $USSDRequestString = "";
        $ussdDailedCode = "";
        try {
            $post = new SimpleXMLElement($raw);
            if (isset($post->TransactionId)) {
                $TransactionId = $post->TransactionId;
            }
            if (isset($post->TransactionTime)) {
                $TransactionTime = $post->TransactionTime;
            }
            if (isset($post->MSISDN)) {
                $MSISDN = $post->MSISDN;
            }
            if (isset($post->USSDServiceCode)) {
                $USSDServiceCode = $post->USSDServiceCode;
            }
            if (isset($post->USSDRequestString)) {
                $USSDRequestString = $post->USSDRequestString;
            }
            if (isset($post->ussdDailedCode)) {
                $ussdDailedCode = $post->ussdDailedCode;
            }
        } catch (\Throwable $th) {
            $post = null;
        }


        if ($TransactionId == null) {
            $TransactionId = "";
        }

        if ($TransactionTime == null) {
            $TransactionTime = "";
        }

        $ussd = null;
        if ((strlen($TransactionId) > 1) && (strlen($TransactionTime) > 1)) {
            $ussd = USSD::where(['session_id' => $TransactionId])
                ->orderBy('id', 'desc')
                ->first();
            if ($ussd == null) {
                $ussd = new USSD();
                $ussd->session_id = $TransactionId;
                $ussd->data = 'home';
                $ussd->service_code = $USSDServiceCode;
                $ussd->TransactionTime = $TransactionTime;
                $ussd->phone_number = $MSISDN;
                $ussd->USSDServiceCode = $USSDServiceCode;
                $ussd->USSDRequestString = $USSDRequestString;
                $ussd->save();
            }
        }

        $data = "";
        $home = "";
        $home .= "1. Register Person with Disability\n";
        $home .= "2. Request for help\n";
        $home .= "3. Gudance and Canceling\n";
        $home .= "4. Events\n";
        $home .= "5. News\n";
        $home .= "6. Jobs\n";
        $home .= "7. Shop\n";
        $home .= "8. Service Providers\n";
        $data = $home;
        $action = "request";
        $person = Person::where(['TransactionId' => $TransactionId])
            ->orderBy('id', 'desc')
            ->first();
        /* 
        ALTER TABLE `people` ADD `registration_method` VARCHAR(45) NULL DEFAULT 'Web' AFTER `profiler`, ADD `` VARCHAR(355) NULL DEFAULT NULL AFTER `registration_method`;

        */

        $USSDRequestString = trim($USSDRequestString);

        if ($USSDRequestString == '0') {

            if ($ussd != null) {
                if ($ussd->data == 'register-first-name') {
                    $ussd->data = 'home';
                    $ussd->save();
                    $data = $home;
                } else if ($ussd->data == 'register-last-name') {
                    $ussd->data = 'register-first-name';
                    $ussd->save();
                    $data = $home;
                } else if ($ussd->data == 'register-sex') {
                    $ussd->data = 'register-last-name';
                    $ussd->save();
                    $data = $home;
                } else if ($ussd->data == 'register-disability') {
                    $ussd->data = 'register-sex';
                    $ussd->save();
                    $data = $home;
                } else if ($ussd->data == 'register-district-letters') {
                    $ussd->data = 'register-disability';
                    $ussd->save();
                    $data = $home;
                } else if ($ussd->data == 'register-district-select') {
                    $ussd->data = 'register-district-letters';
                    $ussd->save();
                    $data = $home;
                } else if ($ussd->data == 'register-education') {
                    $ussd->data = 'register-district-select';
                    $ussd->save();
                    $data = $home;
                }
            }
        }

        if ($ussd != null && $USSDRequestString != '0') {
            if (strlen($USSDRequestString) > 0) {
                if ($ussd->data == 'home') {
                    if ($USSDRequestString == '1') {
                        $data = "Enter First Name";
                        $ussd->data = 'register-first-name';
                        $ussd->save();
                    } else if ($USSDRequestString == '2') {
                        $ussd->data = 'request';
                        $ussd->save();
                    } else if ($USSDRequestString == '3') {
                        $ussd->data = 'gudance';
                        $ussd->save();
                    } else if ($USSDRequestString == '4') {
                        $ussd->data = 'events';
                        $ussd->save();
                    } else if ($USSDRequestString == '5') {
                        $ussd->data = 'news';
                        $ussd->save();
                    } else if ($USSDRequestString == '6') {
                        $ussd->data = 'jobs';
                        $ussd->save();
                    } else if ($USSDRequestString == '7') {
                        $ussd->data = 'shop';
                        $ussd->save();
                    } else if ($USSDRequestString == '8') {
                        $ussd->data = 'service_providers';
                        $ussd->save();
                    }
                } else if ($ussd->data == 'register-first-name') {

                    if (strlen($USSDRequestString) < 2) {
                        $data = "First Name too short.\nRe-Enter First Name";
                        $ussd->data = 'register-first-name';
                        $ussd->save();
                    } else if (strlen($USSDRequestString) > 45) {
                        $data = "First Name too long.\nRe-Enter First Name";
                        $ussd->data = 'register-first-name';
                        $ussd->save();
                    } else {
                        if ($person == null) {
                            $person = new Person();
                        }
                        $person->name = $USSDRequestString;
                        $person->TransactionId = $TransactionId;
                        $person->phone_number = $MSISDN;
                        $person->registration_method = 'USSD';
                        $ussd->data = 'register-last-name';
                        $person->save();
                        $ussd->save();
                        $data = "Enter Last Name";
                    }
                } else if ($ussd->data == 'register-last-name') {
                    if (strlen($USSDRequestString) < 2) {
                        $data = "Last Name too short.\nRe-Enter Last Name";
                        $ussd->data = 'register-last-name';
                        $ussd->save();
                    } else if (strlen($USSDRequestString) > 45) {
                        $data = "Last Name too long.\nRe-Enter Last Name";
                        $ussd->data = 'register-last-name';
                        $ussd->save();
                    } else {
                        $person->name .= " " . $USSDRequestString;
                        $person->other_names = $USSDRequestString;
                        $person->save();
                        $ussd->data = 'register-sex';
                        $ussd->save();
                        $data = "Select Gender\n";
                        $data .= "1. Male\n";
                        $data .= "2. Female\n";
                    }
                } else if ($ussd->data == 'register-sex') {
                    if ($USSDRequestString != '1' && $USSDRequestString != '2') {
                        $data = "Select valid gender (1 or 2)\n";
                        $data .= "1. Male\n";
                        $data .= "2. Female\n";
                        $ussd->data = 'register-sex';
                        $ussd->save();
                    } else {
                        $ussd->data = 'register-disability';
                        $ussd->save();
                        $data = "Select Disability\n";
                        $data .= "1. Autism\n";
                        $data .= "2. Visual impairment\n";
                        $data .= "3. Deaf\n";
                        $data .= "4. Physical disability\n";
                        $data .= "5. Mental health conditions\n";
                        $data .= "6. Albinism\n";
                    }
                } else if ($ussd->data == 'register-disability') {

                    if (
                        $USSDRequestString != '1' &&
                        $USSDRequestString != '2' &&
                        $USSDRequestString != '3' &&
                        $USSDRequestString != '4' &&
                        $USSDRequestString != '5' &&
                        $USSDRequestString != '6'
                    ) {
                        $ussd->data = 'register-disability';
                        $ussd->save();
                        $data = "Select Valid Disability\n";
                        $data .= "1. Autism\n";
                        $data .= "2. Visual impairment\n";
                        $data .= "3. Deaf\n";
                        $data .= "4. Physical disability\n";
                        $data .= "5. Mental health conditions\n";
                        $data .= "6. Albinism\n";
                    } else {
                        $dis = new Person();
                        $dis->person_id = $person->id;
                        $ussd->data = 'register-district-letters';
                        if ($USSDRequestString == '1') {
                            $dis->disability_id = 1;
                            $person->disability = 'Autism';
                        } else if ($USSDRequestString == '2') {
                            $dis->disability_id = 2;
                            $person->disability = 'Visual impairment';
                        } else if ($USSDRequestString == '3') {
                            $dis->disability_id = 3;
                            $person->disability = 'Deaf';
                        } else if ($USSDRequestString == '4') {
                            $dis->disability_id = 7;
                            $person->disability = 'Physical disability';
                        } else if ($USSDRequestString == '5') {
                            $dis->disability_id = 4;
                            $person->disability = 'Mental health conditions';
                        } else if ($USSDRequestString == '6') {
                            $dis->disability_id = 8;
                            $person->disability = 'Albinism';
                        }
                        $person->save();
                        $dis->save();
                        $ussd->save();
                        $data = "Enter at least 3 leters of your district\n";
                    }
                } else if ($ussd->data == 'register-district-letters') {

                    $dist = District::where('name', 'like', '%' . $USSDRequestString . '%')
                        ->limit(6)
                        ->get();
                    if (count($dist) == 0) {
                        $ussd->data = 'register-district-letters';
                        $ussd->save();
                        $data = "Enter at least 3 leters of your district\n";
                    } else {
                        $ussd->data = 'register-district-select';
                        $person->district_search = $USSDRequestString;
                        $person->save();
                        $ussd->save();
                        $data = "Select District\n";
                        $i = 1;
                        foreach ($dist as $d) {
                            $data .= $i . ". " . $d->name . "\n";
                            $i++;
                        }
                        $ussd->data = 'register-district-select';
                    }
                } else if ($ussd->data == 'register-district-select') {
                    $dist = District::where('name', 'like', '%' . $person->district_search . '%')
                        ->limit(6)
                        ->get();
                    $i = 1;
                    $found = false;
                    foreach ($dist as $d) {
                        if ($USSDRequestString == $i . '') {
                            $person->district_id = $d->id;
                            $person->district_search = $d->name;
                            $person->save();
                            $found = true;
                            break;
                        }
                        $i++;
                    }
                    if (!$found) {
                        $ussd->data = 'register-district-select';
                        $ussd->save();
                        $data = "Select District\n";
                        $i = 1;
                        foreach ($dist as $d) {
                            $data .= $i . ". " . $d->name . "\n";
                            $i++;
                        }
                    } else {
                        $ussd->data = 'register-education';
                        $ussd->save();
                        $data = "Education Level\n";
                        $data .= "1. Primary\n";
                        $data .= "2. Secondary\n";
                        $data .= "3. A-Level\n";
                        $data .= "4. Bachelors\n";
                        $data .= "5. P.h.D\n";
                        $data .= "6. None\n";
                    }
                } else if ($ussd->data == 'register-education') {
                    if (
                        $USSDRequestString != '1' &&
                        $USSDRequestString != '2' &&
                        $USSDRequestString != '3' &&
                        $USSDRequestString != '4' &&
                        $USSDRequestString != '5' &&
                        $USSDRequestString != '6'
                    ) {
                        $ussd->data = 'register-education';
                        $ussd->save();
                        $data = "Select Valid Education Level\n";
                        $data .= "1. Primary\n";
                        $data .= "2. Secondary\n";
                        $data .= "3. A-Level\n";
                        $data .= "4. Bachelors\n";
                        $data .= "5. P.h.D\n";
                        $data .= "6. None\n";
                    } else {

                        if ($USSDRequestString == '1') {
                            $person->education_level = 'Primary';
                        } else if ($USSDRequestString == '2') {
                            $person->education_level = 'Secondary';
                        } else if ($USSDRequestString == '3') {
                            $person->education_level = 'A-Level';
                        } else if ($USSDRequestString == '4') {
                            $person->education_level = 'Bachelors';
                        } else if ($USSDRequestString == '5') {
                            $person->education_level = 'P.h.D';
                        } else if ($USSDRequestString == '6') {
                            $person->education_level = 'None';
                        }

                        $ussd->data = 'register-education';
                        $ussd->save();
                        $data = "Registration successful.\nYou are going to receive a call from NUDIPU to confirm your registration.\n";
                        $data .= "THANK YOU!";
                        $action = "end";
                    }
                }
            }
        }



        $myResp = '<?xml version="1.0"?>
        <USSDResponse>' .
            $TransactionId .
            $TransactionTime .
            '<USSDResponseString>' .
            "NUDIPU USSD\n" . $data . '</USSDResponseString>' .
            '<USSDAction>' . $action . '</USSDAction>' .
            '</USSDResponse>';
        if ($ussd != null) {
            $ussd->my_response = $myResp;
            $ussd->save();
        }
        die($myResp);
    }
}
