@extends('layout.app')
@section('title', 'Rules')
@section('content')
        <div class="container-fluid">
            <div class="card" style="background-color: rgba(255, 248, 248, 0.791); box-shadow: 0 2px 10px rgba(0, 0, 0, .1); padding:20px">
                <div class="card-body">
            <h4 style="font-weight: 1000; color: #272323">NSE</h4>
            <ul style="color:#000000;">
                <li>
                <p><strong>Trading starts at 09:16 IST, trading closes at 15:30 IST however position square off is allowed till 15:35.</strong></p>
                </li>
                <li>
                <p><strong>Pending Trades will be automatically deleted after market closes.</strong></p>
                </li>
                <li>
                <p><strong>Dividend will be subtracted from portfolio before the day of dividend.</strong></p>
                </li>
                <li>
                <p><strong>Platform will not accept limit while market is closed.</strong></p>
                </li>
                <li>
                <p><strong>Limit cannot be set above 4% of ask/bid rates respectively.</strong></p>
                </li>
                <li>
                <p><strong>Limit has to be outside the range of ask/bid respectively.</strong></p>
                </li>
                <li>
                <p><strong>Buy Limit/Sell Limit and Buy Stop Loss/Sell Stop Loss will executed buy Bid/Ask prices and NOT LTP respectively.</strong></p>
                </li>
                <li>
                <p><strong>Only position square off will be allowed in banned scripts, no new trades will be accepted.</strong></p>
                </li>
                <li>
                <p><strong>During Buy Circuit, no new buy will be accepted or sell position will be allowed to square off, vice versa during Sell Circuit.</strong></p>
                </li>
                <li>
                <p><strong>Executed Trades cannot be edited or deleted by masters or users or system admin.</strong></p>
                </li>
                <li>
                <p><strong>Line Trades will be nulled by System Admin anytime.</strong></p>
                </li>
                <li>
                <p><strong>Buying forward and Bill generation will be done at 11:00 Every Saturday IST.</strong></p>
                </li>
                <li>
                <p><strong>Quantity and Maximum Position per script will be based upon your account type which can been checked in Futures-&gt;Max Quantity Details</strong></p>
                </li>
            </ul>
            <h4 style="font-weight: 1000; color: #272323">MCX</h4>
            <ul style="color:#000000;">
                <li>
                <p><strong>Trading starts at 09:01 IST, trading closes at 23:30/23:55 IST however position square off is allowed till 23:35/23:55 based upon daylight savings.</strong></p>
                </li>
                <li>
                <p><strong>Pending Trades will be automatically deleted after market closes.</strong></p>
                </li>
                <li>
                <p><strong>Platform will not accept limit while market is closed.</strong></p>
                </li>
                <li>
                <p><strong>Limit cannot be set above 4% of ask/bid rates respectively.</strong></p>
                </li>
                <li>
                <p><strong>Limit has to be outside the range of ask/bid respectively.</strong></p>
                </li>
                <li>
                <p><strong>Buy Limit/Sell Limit and Buy Stop Loss/Sell Stop Loss will executed buy Bid/Ask prices and NOT LTP respectively.</strong></p>
                </li>
                <li>
                <p><strong>During Buy Circuit, no new buy will be accepted or sell position will be allowed to square off, vice versa during Sell Circuit.</strong></p>
                </li>
                <li>
                <p><strong>Executed Trades cannot be edited or deleted by masters or users or system admin.</strong></p>
                </li>
                <li>
                <p><strong>Line Trades will be nulled by System Admin anytime.</strong></p>
                </li>
                <li>
                <p><strong>Buying forward and Bill generation will be done at 11:00 Every Saturday IST.</strong></p>
                </li>
                <li>
                <p><strong>Quantity and Maximum Position per script will be based upon your account type which can been checked in Futures-&gt;Max Quantity Details</strong></p>
                </li>
            </ul>
            <h4 style="font-weight: 1000; color: #272323">Global Futures</h4>
            <ul style="color:#000000;">
                <li>
                <p><strong>Trading starts and ends for each Index at their respective exchange time.</strong></p>
                </li>
                <li>
                <p><strong>Pending Trades will be automatically deleted after market closes.</strong></p>
                </li>
                <li>
                <p><strong>Platform will not accept limit while market is closed.</strong></p>
                </li>
                <li>
                <p><strong>Limit cannot be set above 4% of ask/bid rates respectively.</strong></p>
                </li>
                <li>
                <p><strong>Limit has to be outside the range of ask/bid respectively.</strong></p>
                </li>
                <li>
                <p><strong>Buy Limit/Sell Limit and Buy Stop Loss/Sell Stop Loss will executed buy Bid/Ask prices and NOT LTP respectively.</strong></p>
                </li>
                <li>
                <p><strong>During Buy Circuit, no new buy will be accepted or sell position will be allowed to square off, vice versa during Sell Circuit.</strong></p>
                </li>
                <li>
                <p><strong>Executed Trades cannot be edited or deleted by masters or users or system admin.</strong></p>
                </li>
                <li>
                <p><strong>Line Trades will be nulled by System Admin anytime.</strong></p>
                </li>
                <li>
                <p><strong>Buying forward and Bill generation will be done at 11:00 Every Saturday IST.</strong></p>
                </li>
                <li>
                <p><strong>Quantity and Maximum Position per script will be based upon your account type which can been checked in Futures-&gt;Max Quantity Details</strong></p>
                </li>
            </ul>
            <h4 style="font-weight: 1000; color: #272323">Cricket</h4>
            <ul style="color:#000000;">
                <li>
                <p><strong>All fancy bets will be validated when match has been tied.</strong></p>
                </li>
                <li>
                <p><strong>All advance fancy will be suspended before toss or weather condition.</strong></p>
                </li>
                <li>
                <p><strong>In case technical error or any circumstances any fancy is suspended and does not resume result will be given all previous bets will be valid (based on haar/jeet).</strong></p>
                </li>
                <li>
                <p><strong>If any case wrong rate has been given in fancy that particular bets will be cancelled.</strong></p>
                </li>
                <li>
                <p><strong>In any circumstances management decision will be final related to all exchange items. Our scorecard will be considered as valid if there is any mismatch in online portal</strong></p>
                </li>
                <li>
                <p><strong>In case customer make bets in wrong fancy we are not liable to delete, no changes will be made and bets will be consider as confirm bet.</strong></p>
                </li>
                <li>
                <p><strong>Due to any technical error market is open and result has came all bets after result will be deleted.</strong></p>
                </li>
                <li>
                <p><strong>Manual bets are not accepted in our exchange</strong></p>
                </li>
                <li>
                <p><strong>Our exchange will provide 5 second delay in our TV.</strong></p>
                </li>
                <li>
                <p><strong>Company reserves the right to suspend/void any id/bets if the same is found to be illegitimate. For example in case of vpn/robot-use/multiple entry from same IP and others. Note : only winning bets will be voided</strong></p>
                </li>
                <li>
                <p><strong>Once our exchange gives username and password it is your responsibility to change a password.</strong></p>
                </li>
                <li>
                <p><strong>Penalty runs will not be counted in any fancy.</strong></p>
                </li>
                <li>
                <p><strong>Warning: - live scores and other data on this site is sourced from third party feeds and may be subject to time delays and/or be inaccurate. If you rely on this data to place bets, you do so at your own risk. Our exchange does not accept responsibility for loss suffered as a result of reliance on this data.</strong></p>
                </li>
                <li>
                <p><strong>Our exchange is not responsible for misuse of client id.</strong></p>
            
                <ul>
                    <li>
                    <ul>
                        <li>
                        <ul>
                            <li>
                            <ul>
                                <li>
                                <ul>
                                    <li>
                                    <p><strong>Test</strong></p>
                                    </li>
                                </ul>
                                </li>
                            </ul>
                            </li>
                        </ul>
                        </li>
                    </ul>
                    </li>
                </ul>
                </li>
                <li>
                <p><strong>Session</strong></p>
                </li>
                <li>
                <p><strong>Complete session valid in test.</strong></p>
                </li>
                <li>
                <p><strong>Session is not completed for ex:- India 60 over run session Ind is running in case India team declares or all-out at 55 over next 5 over session will be continue in England inning.</strong></p>
                </li>
                <li>
                <p><strong>1st day 1st session run minimum 25 over will be played then result is given otherwise 1st day 1st session will be deleted.</strong></p>
                </li>
                <li>
                <p><strong>1st day 2nd session run minimum 25 over will be played then result is given otherwise 1st day 2nd session will be deleted.</strong></p>
                </li>
                <li>
                <p><strong>1st day total run minimum 80 over will be played then result is given otherwise 1st day total run will be deleted.</strong></p>
                </li>
                <li>
                <p><strong>Test match both advance session is valid.</strong></p>
            
                <ul>
                    <li>
                    <p><strong>Test lambi/ Inning run:-</strong></p>
                    </li>
                </ul>
                </li>
                <li>
                <p><strong>Mandatory 70 over played in test lambi paari/ Innings run. If any team all-out or declaration lambi paari/ innings run is valid.</strong></p>
                </li>
                <li>
                <p><strong>In case due to weather situation match has been stopped all lambi trades will be deleted.</strong></p>
                </li>
                <li>
                <p><strong>In test both lambi paari / inning run is valid in advance fancy.</strong></p>
                </li>
                <li>
                <p><strong>Test batsman:-</strong></p>
                </li>
                <li>
                <p><strong>In case batsmen is injured he/she is made 34 runs the result will be given 34 runs.</strong></p>
                </li>
                <li>
                <p><strong>Batsman 50/100 run if batsman is injured or declaration the result will be given on particular run.</strong></p>
                </li>
                <li>
                <p><strong>In next men out fancy if player is injured particular fancy will be deleted.</strong></p>
                </li>
                <li>
                <p><strong>In advance fancy opening batsmen is only valid if same batsmen came in opening the fancy will be valid in case one batsmen is changed that particular player fancy will be deleted.</strong></p>
                </li>
                <li>
                <p><strong>Test match both advance fancy batsmen run is valid.</strong></p>
                </li>
                <li>
                <p><strong>Test partnership:-</strong></p>
                </li>
                <li>
                <p><strong>In partnership one batsman is injured partnership is continued in next batsman.</strong></p>
                </li>
                <li>
                <p><strong>Partnership and player runs due to weather condition or match abandoned the result will be given as per score.</strong></p>
                </li>
                <li>
                <p><strong>Advance partnership is valid in case both players are different or same.</strong></p>
                </li>
                <li>
                <p><strong>Test match both advance fancy partnership is valid.</strong></p>
                </li>
                <li>
                <p><strong>Other fancy advance (test):-</strong></p>
                </li>
                <li>
                <p><strong>Four, sixes, wide, wicket, extra run, total run, highest over and top batsmen is valid only if 300 overs has been played or the match has been won by any team otherwise all these fancy will be deleted.</strong></p>
            
                <ul>
                    <li>
                    <ul>
                        <li>
                        <ul>
                            <li>
                            <ul>
                                <li>
                                <ul>
                                    <li>
                                    <p><strong>ODI:-</strong></p>
                                    </li>
                                </ul>
                                </li>
                            </ul>
                            </li>
                        </ul>
                        </li>
                    </ul>
                    </li>
                </ul>
                </li>
                <li>
                <p><strong>Session:-</strong></p>
                </li>
                <li>
                <p><strong>Match 1st over run advance fancy only 1st innings run will be counted.</strong></p>
                </li>
                <li>
                <p><strong>Complete session is valid in case due to rain or match abandoned particular session will be deleted.</strong></p>
                </li>
                <li>
                <p><strong>For example: - 35 over run team a is playing any case team A is all-out in 33 over team a has made 150 run the session result is validated on particular run.</strong></p>
                </li>
                <li>
                <p><strong>Advance session is valid in only 1st innings.</strong></p>
                </li>
                <li>
                <p><strong>50 over runs:-</strong></p>
                </li>
                <li>
                <p><strong>In case 50 over is not completed all bet will be deleted due to weather or any condition.</strong></p>
                </li>
                <li>
                <p><strong>Advance 50 over runs is valid in only 1st innings.</strong></p>
                </li>
                <li>
                <p><strong>ODI batsman runs:-</strong></p>
                </li>
                <li>
                <p><strong>In case batsman is injured he/she is made 34 runs the result will be given 34 runs.</strong></p>
                </li>
                <li>
                <p><strong>In next men out fancy if player is injured particular fancy will be deleted.</strong></p>
                </li>
                <li>
                <p><strong>In advance fancy opening batsmen is only valid if same batsmen came in opening the fancy will be valid in case one batsmen is changed that particular player fancy will be deleted.</strong></p>
                </li>
                <li>
                <p><strong>ODI partnership runs:-</strong></p>
                </li>
                <li>
                <p><strong>In partnership one batsman is injured partnership is continued in next batsman.</strong></p>
                </li>
                <li>
                <p><strong>Advance partnership is valid in case both players are different or same.</strong></p>
                </li>
                <li>
                <p><strong>Both team advance partnerships are valid in particular match.</strong></p>
                </li>
                <li>
                <p><strong>Other fancy:-</strong></p>
                </li>
                <li>
                <p><strong>Four, sixes, wide, wicket, extra run, total run, highest over ,top batsman, maiden over,caught-out,no-ball,run-out,fifty and century are valid only match has been completed in case due to rain over has been reduced all other fancy will be deleted.</strong></p>
            
                <ul>
                    <li>
                    <ul>
                        <li>
                        <ul>
                            <li>
                            <ul>
                                <li>
                                <ul>
                                    <li>
                                    <p><strong>T20:-</strong></p>
                                    </li>
                                </ul>
                                </li>
                            </ul>
                            </li>
                        </ul>
                        </li>
                    </ul>
                    </li>
                </ul>
                </li>
                <li>
                <p><strong>Session:-</strong></p>
                </li>
                <li>
                <p><strong>Match 1st over run advance fancy only 1st innings run will be counted.</strong></p>
                </li>
                <li>
                <p><strong>Complete session is valid in case due to rain or match abandoned particular session will be deleted.</strong></p>
                </li>
                <li>
                <p><strong>For example: - 15 over run team a is playing any case team a is all-out in 13 over team A has made 100 run the session result is validated on particular run.</strong></p>
                </li>
                <li>
                <p><strong>Advance session is valid in only 1st innings.</strong></p>
                </li>
                <li>
                <p><strong>20 over runs:-</strong></p>
                </li>
                <li>
                <p><strong>Advance 20 over run is valid only in 1st innings. 20 over run will not be considered as valid if 20 overs is not completed due to any situation</strong></p>
                </li>
                <li>
                <p><strong>T20 batsman runs:-</strong></p>
                </li>
                <li>
                <p><strong>In case batsman is injured he/she is made 34 runs the result will be given 34 runs.</strong></p>
                </li>
                <li>
                <p><strong>In next men out fancy if player is injured particular fancy will be deleted.</strong></p>
                </li>
                <li>
                <p><strong>In advance fancy opening batsmen is only valid if same batsmen came in opening the fancy will be valid in case one batsmen is changed that particular player fancy will be deleted.</strong></p>
                </li>
                <li>
                <p><strong>T20 partnership runs:-</strong></p>
                </li>
                <li>
                <p><strong>In partnership one batsman is injured partnership is continued in next batsman.</strong></p>
                </li>
                <li>
                <p><strong>Advance partnership is valid in case both players are different or same.</strong></p>
                </li>
                <li>
                <p><strong>Both team advance partnerships are valid in particular match. 1st 3 wkt runs:- Advance 1st 3 wkt runs is valid in only 1st Innings</strong></p>
                </li>
                <li>
                <p><strong>Other fancy:-</strong></p>
                </li>
                <li>
                <p><strong>T-20 ,one day and test match in case current innings player and partnership are running in between match has been called off or abandoned that situation all current player and partnership results are valid.</strong></p>
                </li>
                <li>
                <p><strong>Four, sixes, wide, wicket, extra run, total run, highest over and top batsman, maiden over,caught-out,no-ball,run-out,fifty and century are valid only match has been completed in case due to rain over has been reduced all other fancy will be deleted. 1st 6 over dot ball and 20 over dot ball fancy only valid is 1st innings.</strong></p>
                </li>
                <li>
                <p><strong>Lowest scoring over will be considered valid only if the over is completed fully (all six deliveries has to be bowled)</strong></p>
                </li>
                <li>
                <p><strong>1st wicket lost to any team balls meaning that any team 1st wicket fall down in how many balls that particular fancy at least minimum one ball have to be played otherwise bets will be deleted.</strong></p>
                </li>
                <li>
                <p><strong>1st wicket lost to any team fancy valid both innings.</strong></p>
                </li>
                <li>
                <p><strong>How many balls for 50 runs any team meaning that any team achieved 50 runs in how many balls that particular fancy at least one ball have to be played otherwise that fancy bets will be deleted.</strong></p>
                </li>
                <li>
                <p><strong>How many balls for 50 runs fancy any team only first inning valid.</strong></p>
                </li>
                <li>
                <p><strong>1st 6 inning boundaries runs any team fancy will be counting only according to run scored fours and sixes at least 6 over must be played otherwise that fancy will be deleted.</strong></p>
                </li>
                <li>
                <p><strong>1st inning 6 over boundaries runs any team run like wide, no-ball, leg-byes, byes and over throw runs are not counted this fancy.</strong></p>
                </li>
                <li>
                <p><strong>How many balls face any batsman meaning that any batsman how many balls he/she played that particular fancy at least one ball have to be played otherwise that fancy bets will be deleted.</strong></p>
                </li>
                <li>
                <p><strong>How many balls face by any batsman both innings valid.</strong></p>
                </li>
                <li>
                <p><strong>Concussion in Test:-</strong></p>
                </li>
                <li>
                <p><strong>All bets of one over session will be deleted in test scenario, in case session is incomplete. For example innings declared or match suspended to bad light or any other conditions.</strong></p>
                </li>
                <li>
                <p><strong>All bets will be considered as valid if a player has been replaced under concussion substitute, result will be given for the runs scored by the mentioned player. For example DM Bravo gets retired hurt at 23 runs, then result will be given for 23.</strong></p>
                </li>
                <li>
                <p><strong>Bets of both the player will be valid under concussion substitute.</strong></p>
                </li>
                <li>
                <p><strong>Total Match- Events (test):-</strong></p>
                </li>
                <li>
                <p><strong>Minimum of 300 overs to be bowled in the entire test match, otherwise all bets related to the particular event will get void. For example, Total match caught outs will be valid only if 300 overs been bowled in the particular test match</strong></p>
                </li>
                <li>
                <p><strong>Limited over events-Test:-</strong></p>
                </li>
                <li>
                <p><strong>This event will be considered valid only if the number of overs defined on the particular event has been bowled, otherwise all bets related to this event will get void. For example 0-25 over events will be valid only if 25 overs has been bowled, else the same will not be considered as valid</strong></p>
                </li>
                <li>
                <p><strong>If the team gets all out prior to any of the defined overs, then balance overs will be counted in next innings. For example if the team gets all out in 23.1 over the same will be considered as 24 overs and the balance overs will be counted from next innings.</strong></p>
                </li>
                <li>
                <p><strong>Bowler Wicket event's- Test:-</strong></p>
                </li>
                <li>
                <p><strong>Minimum of one legal over (one complete over) has to be bowled by the bowler mentioned in the event, else the same will not be considered as valid</strong></p>
                </li>
                <li>
                <p><strong>Bowler over events- Test:-</strong></p>
                </li>
                <li>
                <p><strong>The mentioned bowler has to complete the defined number of overs; else the bets related to that particular event will get void. For example if the mentioned bowler has bowled 8 overs, then 5 over run of that particular bowler will be considered as valid and the 10 over run will get void</strong></p>
                </li>
                <li>
                <p><strong>Player ball event's- Test:-</strong></p>
                </li>
                <li>
                <p><strong>This event will be considered valid only if the defined number of runs made by the mentioned player, else the result will be considered as 0 (zero) balls</strong></p>
                </li>
                <li>
                <p><strong>For example if Root makes 20 runs in 60 balls and gets out on 22 runs, result for 20 runs will be 60 balls and the result for balls required for 25 run Root will be considered as 0 (Zero) and the same will be given as result</strong></p>
                </li>
                <li>
                <p><strong>Limited over events-ODI:-</strong></p>
                </li>
                <li>
                <p><strong>This event will be considered valid only if the number of overs defined on the particular event has been bowled, otherwise all bets related to this event will get void. 0-50 over events will be valid only if 50 over completed, if the team batting first get all out prior to 50 over the balance over will be counted from second innings. For example if team batting first gets all out in 35 over balance 15 over will be counted from second innings, the same applies for all events if team gets all out before the defined number of overs</strong></p>
                </li>
                <li>
                <p><strong>The events which remains incomplete will be voided if over gets reduced in the match due to any situation, for example if match interrupted in 15 overs due to rain/bad light and post this over gets reduced. Events for 0-10 will be valid; all other events related to this type will get deleted.</strong></p>
                </li>
                <li>
                <p><strong>This events will be valid only if the defined number of over is completed. For example team batting first gets all out in 29.4 over then the same will be considered as 30 over, the team batting second must complete 20 overs only then 0-50 over events will be considered as valid. In case team batting second gets all out in 19.4 over then 0-50 over event will not be considered as valid, this same is valid for 1st Innings only.</strong></p>
                </li>
                <li>
                <p><strong>Bowler event- ODI:-</strong></p>
                </li>
                <li>
                <p><strong>The mentioned bowler has to complete the defined number of overs; else the bets related to that particular event will get void. For example if the mentioned bowler has bowled 8 overs, then 5 over run of that particular bowler will be considered as valid and the 10 over run will get void</strong></p>
                </li>
                <li>
                <p><strong>Both innings are valid</strong></p>
                </li>
                <li>
                <p><strong>Other event:- T20</strong></p>
                </li>
                <li>
                <p><strong>The events for 1-10 over and 11-20 over will be considered valid only if the number of over mentioned has been played completely. However if the over got reduced before the particular event then the same will be voided, if the team batting first get all out prior to 20 over the balance over will be counted from second innings. For example if team batting first gets all out in 17 over balance 3 over will be counted from second innings. This same is valid for 1st Innings only.</strong></p>
                </li>
                <li>
                <p><strong>If over got reduced in between any running event, then the same will be considered valid and the rest will be voided. For example.., match started and due to rain/bad light or any other situation match got interrupted at 4 over and later over got reduced. Then events for 1-10 is valid rest all will be voided</strong></p>
                </li>
                <li>
                <p><strong>Bowler Session: This event is valid only if the bowler has completed his maximum quota of overs, else the same will be voided. However if the match has resulted and the particular bowler has already started bowling his final over then result will be given even if he haven't completed the over. For example B Kumar is bowling his final over and at 3.4 the match has resulted then result will be given for B Kumar over runs</strong></p>
                </li>
                <li>
                <p><strong>In case of DLS, the over got reduced then the bowler who has already bowled his maximum quota of over that result will be considered as valid and the rest will be voided</strong></p>
                </li>
                <li>
                <p><strong>12. Player and partnership are valid only 14 matches.</strong></p>
                </li>
                <li>
                <p><strong>Boundary on Match 1st Free hit</strong></p>
                </li>
                <li>
                <p><strong>Both innings are valid</strong></p>
                </li>
                <li>
                <p><strong>Boundary hit on Free hit only be considered as valid</strong></p>
                </li>
                <li>
                <p><strong>Bets will be deleted if there is no Free hit in the mentioned match</strong></p>
                </li>
                <li>
                <p><strong>Boundary by bat will be considered as valid</strong></p>
                </li>
                <li>
                <p><strong>Boundaries by Player</strong></p>
                </li>
                <li>
                <p><strong>Both Four and six are valid</strong></p>
                </li>
                <li>
                <p><strong>Any query regarding result or rate has to be contacted within 7 days from the event, query after 7 days from the event will not be considered as valid</strong></p>
                </li>
            </ul>
                </div>
            </div>
            
        </div>
@endsection

