<?php

namespace App\Http\Middleware;

use Closure;
use DB;
//use DateTime;

class CheckIP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
            $output = NULL;
            if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
                $ip = $_SERVER["REMOTE_ADDR"];
                if ($deep_detect) {
                    if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                        $ip = $_SERVER['HTTP_CLIENT_IP'];
                }
            }
            $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
            $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
            $continents = array(
                "AF" => "Africa",
                "AN" => "Antarctica",
                "AS" => "Asia",
                "EU" => "Europe",
                "OC" => "Australia (Oceania)",
                "NA" => "North America",
                "SA" => "South America"
            );
            if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
                $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
                if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                    switch ($purpose) {
                        case "location":
                            $output = array(
                                "city"           => @$ipdat->geoplugin_city,
                                "state"          => @$ipdat->geoplugin_regionName,
                                "country"        => @$ipdat->geoplugin_countryName,
                                "country_code"   => @$ipdat->geoplugin_countryCode,
                                "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                                "continent_code" => @$ipdat->geoplugin_continentCode
                            );
                            break;
                        case "address":
                            $address = array($ipdat->geoplugin_countryName);
                            if (@strlen($ipdat->geoplugin_regionName) >= 1)
                                $address[] = $ipdat->geoplugin_regionName;
                            if (@strlen($ipdat->geoplugin_city) >= 1)
                                $address[] = $ipdat->geoplugin_city;
                            $output = implode(", ", array_reverse($address));
                            break;
                        case "city":
                            $output = @$ipdat->geoplugin_city;
                            break;
                        case "state":
                            $output = @$ipdat->geoplugin_regionName;
                            break;
                        case "region":
                            $output = @$ipdat->geoplugin_regionName;
                            break;
                        case "country":
                            $output = @$ipdat->geoplugin_countryName;
                            break;
                        case "countrycode":
                            $output = @$ipdat->geoplugin_countryCode;
                            break;
                    }
                }
            }
            return $output;
        }

        function cidrconv($net) {
            $start = strtok($net,"/");
            $n = 3 - substr_count($net, ".");
            if ($n > 0)
            {
                for ($i = $n;$i > 0; $i--)
                    $start .= ".0";
            }
            $bits1 = str_pad(decbin(ip2long($start)), 32, "0", STR_PAD_LEFT);
            $net = (1 << (32 - substr(strstr($net, "/"), 1))) - 1;
            $bits2 = str_pad(decbin($net), 32, "0", STR_PAD_LEFT);
            $final = "";
            for ($i = 0; $i < 32; $i++)
            {
                if ($bits1[$i] == $bits2[$i]) $final .= $bits1[$i];
                if ($bits1[$i] == 1 and $bits2[$i] == 0) $final .= $bits1[$i];
                if ($bits1[$i] == 0 and $bits2[$i] == 1) $final .= $bits2[$i];
            }
            return array($start, long2ip(bindec($final)));
        }



        $status=DB::table('options')->where('id','1')->value('ip_filter_status');
        if($status==1){
            //get available info
            $visitorip=request()->ip();
            $curtime=date("Y-m-d H:i:s", time());
            if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
                $visitorip= $_SERVER["HTTP_CF_CONNECTING_IP"];
            }
            $visitorcountrycode=ip_info($visitorip, "Country Code");
            $visitorcountry=ip_info($visitorip, "country");

            $whitelist_ipspl=DB::table('whitelist_ips')->pluck('ip');
            $whitelist_ips = json_decode($whitelist_ipspl);
            $cidrfound='';
            $cidrwhitelisted=0;
            foreach($whitelist_ips as $ip){
                if(stripos($ip,'/')!==false){
                    $ipar= cidrconv($ip);
                    $ipasplit=explode('.',$ipar[0]);
                    $ipzsplit=explode('.',$ipar[1]);

                    for ($ia = $ipasplit[0]; $ia <= $ipzsplit[0]; $ia++) {
                        for ($ja = $ipasplit[1]; $ja <= $ipzsplit[1]; $ja++) {
                            for ($ka = $ipasplit[2]; $ka <= $ipzsplit[2]; $ka++) {
                                for ($la = $ipasplit[3]; $la <= $ipzsplit[3]; $la++) {
                                    if($visitorip=="$ia.$ja.$ka.$la"){
                                        $cidrfound="$ia.$ja.$ka.$la";
                                        $cidrwhitelisted=1;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if (!(in_array($visitorip, $whitelist_ips) || $cidrwhitelisted==1)) {


                $useragent=$request->header('User-Agent');
                $requrl=$request->url();



                $whitelist_ipspl=DB::table('whitelist_ips')->pluck('ip');
                $whitelist_ips = json_decode($whitelist_ipspl);
                // print_r(cidrconv('192.168.1.0/24'));
                if (!in_array($visitorip, $whitelist_ips)) {

                    $banned_countries_codepl=DB::table('banned_countries')->pluck('country_code');
                    $banned_countries_code = json_decode($banned_countries_codepl);
                    $banned_countriespl=DB::table('banned_countries')->pluck('country');
                    $banned_countries = json_decode($banned_countriespl);
                    if (in_array($visitorcountrycode, $banned_countries_code) || in_array($visitorcountry, $banned_countries)) {
                        DB::table('banned_ips')->insert([
                            'ip' => $visitorip,
                            'reason' => $visitorcountry.'('.$visitorcountrycode.')',
                            'created_at' => $curtime,
                        ]);
                        $redirect_to=DB::table('options')->where('id','1')->value('redirect_banned');
                        header("Location: http://$redirect_to");
                        die();
                    }
                    $banned_ipspl=DB::table('banned_ips')->pluck('ip');
                    $banned_ips = json_decode($banned_ipspl);


                    $cidrfound='';
                    $cidrbanned=0;
                    foreach($banned_ips as $ip){
                        if(stripos($ip,'/')!==false){
                            $ipar= cidrconv($ip);
                            $ipasplit=explode('.',$ipar[0]);
                            $ipzsplit=explode('.',$ipar[1]);

                            for ($ia = $ipasplit[0]; $ia <= $ipzsplit[0]; $ia++) {
                                for ($ja = $ipasplit[1]; $ja <= $ipzsplit[1]; $ja++) {
                                    for ($ka = $ipasplit[2]; $ka <= $ipzsplit[2]; $ka++) {
                                        for ($la = $ipasplit[3]; $la <= $ipzsplit[3]; $la++) {
                                            if($visitorip=="$ia.$ja.$ka.$la"){
                                                $cidrfound="$ia.$ja.$ka.$la";
                                                $cidrbanned=1;

                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if (in_array($visitorip, $banned_ips) || $cidrbanned==1) {
                        $redirect_to=DB::table('options')->where('id','1')->value('redirect_banned');
                        header("Location: http://$redirect_to");
                        die();
                    }
                    //if request is sent by chrome and firefox at the same time, ban the ip
                    if((stripos($useragent,"firefox")!==false && stripos($useragent,"chrome")!==false)){

                        DB::table('banned_ips')->insert([
                            'ip' => $visitorip,
                            'reason' => $useragent,
                            'created_at' => $curtime,
                        ]);
                        $del=DB::table('visited_ips')->where('ip', $visitorip)->delete();

                    }elseif(
                        stripos($useragent,"firefox")!==false ||
                        stripos($useragent,"chrome")!==false ||
                        stripos($useragent,"Seamonkey")!==false ||
                        stripos($useragent,"Chromium")!==false ||
                        stripos($useragent,"Safari")!==false ||
                        stripos($useragent,"OPR")!==false ||
                        stripos($useragent,"Opera")!==false ||
                        stripos($useragent,"MSIE")!==false ||
                        stripos($useragent,"Gecko")!==false ||
                        stripos($useragent,"AppleWebKit")!==false ||
                        stripos($useragent,"Trident")!==false ||
                        stripos($useragent,"Mozilla")!==false
                    ){
                        DB::table('banned_ips')->insert([
                            'ip' => $visitorip,
                            'reason' => $useragent,
                            'created_at' => $curtime,
                        ]);
                        $del=DB::table('visited_ips')->where('ip', $visitorip)->delete();
                    }



                    //check if ip is already suspicious
                    $IpExists = DB::table('visited_ips')->where('ip', $visitorip)->get();
                    DB::table('visited_ips')->where('created_at','<=', date("Y-m-d H:i:s", strtotime("-1 minutes", time())))->delete();
                    if (isset($IpExists[0])) {



                        //calculate time since last visit (in seconds)
                        $timeFirst  =  strtotime($IpExists[0]->created_at);
                        $timeSecond =  strtotime($curtime);
                        $differenceInSeconds =$timeSecond- $timeFirst;

//                        $datetime1 = new DateTime($IpExists[0]->created_at);
//                        $datetime2 = new DateTime($curtime);
//$interval = date_diff($datetime1,$datetime2);
//$testval= $IpExists[0]->created_at;
                        //   $differenceInSeconds=$interval->s;

                        if( $differenceInSeconds<0){
                            DB::table('visited_ips')
                                ->where([
                                    ['ip', $visitorip],
                                ])->update([
                                    'created_at' => $curtime,
                                ]);
                        }


                        //if the url is the same as first visit, the time difference is lower than 4 seconds and its the strike 3, ban
                        if($IpExists[0]->times>2 && $differenceInSeconds>=0){

                            DB::table('banned_ips')->insert([
                                'ip' => $visitorip,
                                'reason' => $requrl,
                                'created_at' => $curtime,
                            ]);
                            $del=DB::table('visited_ips')->where('ip','<', $visitorip)->delete();

                            //if the time difference from first visit at the same url is lower than 4 seconds, ip gets a strike
                        }elseif($IpExists[0]->url==$requrl && $differenceInSeconds<=4 && $differenceInSeconds>=0){
                            $timedadd=$IpExists[0]->times+1;
                            DB::table('visited_ips')
                                ->where([
                                    ['ip', $visitorip],
                                ])->update([
                                    'times' => $timedadd,
                                ]);
                        }elseif($differenceInSeconds>=5){
                            $del=DB::table('visited_ips')->where('ip', $visitorip)->delete();
                        }
                        if($IpExists[0]->url!=$requrl){
                            DB::table('visited_ips')
                                ->where([
                                    ['ip', $visitorip],
                                ])->update([
                                    'url' => $requrl,
                                    'times' => 1,
                                ]);
                        }

                    } else {
                        DB::table('visited_ips')->insert([
                            'ip' => $visitorip,
                            'times' => 1,
                            'url' => $requrl,
                            'created_at' => $curtime,
                        ]);
                    }
                }
            }
        }
        return $next($request);
    }
}
