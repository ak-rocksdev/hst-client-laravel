@php $groupedContestants = $contestants->groupBy('ID_competition'); @endphp
    @foreach ($groupedContestants as $competitionId => $contestants)
    <div class="row mb-4">
        <div id="contestant-container" class="col-sm-12">
            <div class="table-header-gradient d-flex flex-row align-items-center p-3">
                <img class="me-2" src="{{ asset('assets/img/logo_light.png') }}">
                <div class="d-flex flex-column table-title-container">
                    <div class="fw-bold fs-3">{{ $contestants[0]->event_name }} - {{ $contestants[0]->sport_name }}</div>
                    <div class="fw-normal">{{ __('messages.category') }} : {{ $contestants[0]->competition_name }}</div>
                </div>
            </div>
            <div class="table-container">
                <table id="myTable" class="table table-dark table-striped text-uppercase">
                    <thead>
                        <tr colspan="3">
                            <th width="15%"><span class="fw-bold">NO.</span></th>
                            <th width="45%"><span class="fw-bold">{{ __('messages.name') }}</span></th>
                            <th width="40%"><span class="fw-bold">{{ __('messages.origin') }}</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contestants as $key => $contestant)
                        <tr>
                            <td>{{ $key + 1 }}.</td>
                            <td>{{ $contestant->full_name }}</td>
                            <td>
                                @if ($contestant->indo_province_name)
                                    {{ $contestant->indo_province_name }},
                                @endif
                                @if ($contestant->indo_city_name)
                                    {{ $contestant->indo_city_name }},
                                @endif
                                @if ($contestant->state_name)
                                    {{ $contestant->state_name }},
                                @endif
                                {{ $contestant->country_name ? $contestant->country_name : '(Update your Profile)' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach


    @foreach ($contestantsOnFinal as $index => $contestant)
                            <tr>
                                <td class="titleContent numberIncrement text-center">{{ $index + 1 }}</td>
                                <td class="titleContent nameList">{{ $contestantNames[$contestant->ID_user] }}</td>
                            </tr>
                            @endforeach

<div class="tableContainer" style="width: 50%;">
                    <!-- NOTE -->
                    <div class="innerTableContainer">
                        <table id="myTable" class="table table-striped table-responsive table-borderless text-uppercase tableOverflow">
                            <div class="tableShadowLeft"></div>
                            <tbody>
                                <tr>
                                    @for ($i = 1; $i <= count($competition->gamesForFinals); $i++)
                                        <th class="titleContent text-center">F{{ $i }}</th>
                                    @endfor
                                    <th class="titleContent text-center">Final</th>
                                    <th class="titleContent text-center">Point</th>
                                </tr>
                                @foreach ($contestantsOnFinal as $index => $contestant)
                                    <tr>
                                        @foreach($gamesForFinal->pluck('ID_games') as $game)
                                            @if(isset($scoresByFinal[$contestant->ID_contestant][$game]))
                                                <td class="titleContent text-center">
                                                    {{ $scoresByFinal[$contestant->ID_contestant][$game]['score'] }}
                                                    @if($scoresByFinal[$contestant->ID_contestant][$game]['highlights'])
                                                        <span class="highlight">H</span>
                                                    @endif
                                                </td>
                                            @else
                                                <td class="titleContent text-center">N/A</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                            <div class="tableShadowRight"></div>
                        </table>
                    </div>
                </div>