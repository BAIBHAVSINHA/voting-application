<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\VarDumper\Cloner\Data;

class CandidatesContoller extends Controller
{
    //



    public function home(){

        $candidates = DB::table('candidates')->get(); //an array

        return view('home',['candidates'=> $candidates]);

        
    }





    public function createCandidate(Request $request){

       $candidateName =  $request->input('candidateName');
       $candidateInfo = $request->input('candidateInfo');

       if($candidateInfo == null){
           $candidateInfo = "";
       }

       DB::table('candidates')->insert(
           ['name'=>$candidateName, 'votes'=>0, 'information'=> $candidateInfo]
       );


       return redirect('/createCandidateForm');


    }

    public function  createCandidateForm(){

        if(Auth::check()  && Auth::user()->is_admin){
                 return view("createCandidateForm");
        }else{
               return \redirect('/')->with('flashMessageProblem','You are not authorized to enter this page');
        }
    }

    public function votingPage(){


        //if user is not logged in then send them to register
        if(!Auth::check())
        {

                return \redirect('/register');

            //check if user has already voted or not
        }else if(now() > date('2021-06-14 00:00:00')){

           return \redirect('/')->with('flashMessageProblem','You can no longer vote. Polls closed on 3 June 2021');

        }else if(!Auth::user()->has_voted){

            $candidates = DB::table('candidates')->get(); //an array
            return view('voting',['candidates'=> $candidates]);
         }

    //   else if(now() > date('2021-06-06 00:00:00'))
    //   {
    //      return \redirect('/')->with('flashMessageProblem','You can no longer vote. Polls closed on 3 June 2021');
    //   }

        else{

            //user has already voted
            return \redirect('/')->with('flashMessageProblem','You have already voted');

        }
       
    }


    public function castYourVote(Request $request){

       $candidateId = $request->input('candidateId');

        //increase number of votes
        DB::table('candidates')->where('id',$candidateId)
                ->update([
                    'votes'=>DB::raw("votes + 1")
                ]);


        //change the has_voted value from 0 to 1
        DB::table('users')->where('id',Auth::user()->id)  
                    ->update([
                        'has_voted'=>1
                    ]);


        //store which candidate user has voted for  
        DB::table('users')->where('id',Auth::user()->id)
                        ->update([
                            'candidate_voted_for'=> $candidateId
                        ]);

        // return but with a message
       // return view('home');   
       return \redirect('/')->with('flashMessage','You voted successfully. Results will be available on 6 June 2021 (Sunday)');     



    }
}
