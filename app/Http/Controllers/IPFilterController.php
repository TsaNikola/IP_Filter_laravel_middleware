<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use \Input as Input;
use \Illuminate\Foundation\Exceptions\Handler;


class IPFilterController extends Controller
{

    public function ipfilter(Request $request)
    {
//        if(Auth::user()){
//return null;
            $status=DB::table('options')->where('id','1')->value('ip_filter_status');
//            return $status;
            $redirect_banned=DB::table('options')->where('id','1')->value('redirect_banned');
            $whitelisted=DB::table('whitelist_ips')->get();
            $banned=DB::table('banned_ips')->get();
            $banned_countries=DB::table('banned_countries')->get();
            $suspicious=DB::table('visited_ips')->get();
            return view('index', compact('redirect_banned','whitelisted','banned','suspicious','status','banned_countries'));
//        }
    }

    public function ipfilterupdate(Request $request)
    {

//        if(Auth::user()){


//            $data =  Input::except(array('_token')) ;

            $inputs = $request->all();

            if(isset($inputs['status'])){
                DB::table('options')
                    ->where([
                        ['id', '1'],
                    ])->update([
                        'ip_filter_status' => $inputs['status'],
                    ]);
            }
            if(isset($inputs['deletewl'])){
                DB::table('whitelist_ips')->where('id' ,$inputs['ipid'])->delete();
            }
            if(isset($inputs['savewhitelisted'])){
                //   return $inputs;
                if(isset($inputs['ipid'])){
                    DB::table('whitelist_ips')
                        ->where([
                            ['id', $inputs['ipid']],
                        ])->update([
                            'ip' => $inputs['whitelisted'],
                        ]);
                }else{
                    DB::table('whitelist_ips')->insert([
                        'ip' => $inputs['whitelisted'],
                    ]);
                }
            }elseif(isset($inputs['deletebanned'])){
                DB::table('banned_ips')->where('id' ,$inputs['ipid'])->delete();
            }elseif(isset($inputs['savebanned'])){

                if(isset($inputs['ipid'])){
                    DB::table('banned_ips')
                        ->where([
                            ['id', $inputs['ipid']],
                        ])->update([
                            'ip' => $inputs['banned'],
                            'reason' => $inputs['reason'],
                            'created_at' => $inputs['bandate'],
                        ]);
                }else{
                    DB::table('banned_ips')->insert([
                        'ip' => $inputs['banned'],
                        'reason' => $inputs['reason'],
                        'created_at' => $inputs['bandate'],
                    ]);
                }
            }elseif(isset($inputs['deletecountry'])){
                DB::table('banned_countries')->where('id' ,$inputs['id'])->delete();
            }elseif(isset($inputs['savecountry'])){
                if(isset($inputs['ipid'])){
                    DB::table('banned_countries')
                        ->where([
                            ['id', $inputs['id']],
                        ])->update([
                            'country' => $inputs['country'],
                            'country_code' => $inputs['country_code'],
                            'created_at' => $inputs['bandate'],
                        ]);
                }else{
                    DB::table('banned_countries')->insert([
                        'country' => $inputs['country'],
                        'country_code' => $inputs['country_code'],
                        'created_at' => $inputs['bandate'],
                    ]);
                }

            }elseif(isset($inputs['redirect_url'])){
                DB::table('options')
                    ->where([
                        ['id', '1'],
                    ])->update([
                        'redirect_banned' => $inputs['redirect_url'],
                    ]);
            }

//        }
        return redirect()->back();
    }


}
