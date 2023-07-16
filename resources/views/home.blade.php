<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <title>Champions League</title>
</head>
<body>
<div id="app"></div>

<!-- Bootstrap core JavaScript -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

<!-- ReactJs -->
<script src="{{ asset('js/babel.min.js') }}"></script>
<script src="{{ asset('js/react.development.js') }}"></script>
<script src="{{ asset('js/react-dom.development.js') }}"></script>

<<script type="text/babel">
    class App extends React.Component {
        constructor(props) {
            super(props);
            this.resetLeague = this.resetLeague.bind(this);
            this.playAll = this.playAll.bind(this);
            this.playWeek = this.playWeek.bind(this);
            this.getTeams = this.getTeams.bind(this);
            this.getChampionship = this.getChampionship.bind(this);
            this.getStanding = this.getStanding.bind(this);
            this.getFixture = this.getFixture.bind(this);
            this.rebuildAllData = this.rebuildAllData.bind(this);

            this.state = {
                fixture: [],
                league: {"id": 1, "name": "Süper Lig", "created_at": null, "updated_at": null},
                teams: [],
                standings: [],
                championship: [],
                activeWeek: 0,
            };
        }

        getTeams = () => {
            return fetch('/api/team')
                .then(response => response.json())
                .then(json => {
                    const teams = [];
                    json.data.forEach(team => {
                        teams[team.id] = team.name
                    });

                    this.setState({teams: teams});

                    return teams;
                })
                .catch(error => {
                    console.error(error);
                });
        };

        getFixture = () => {
            return fetch('/api/fixture')
                .then(response => response.json())
                .then(json => {
                    const groupedData = [];
                    var activeWeek = 0;
                    json.data.forEach(item => {
                        const week = item.week;
                        if (! groupedData[week]) {
                            groupedData[week] = [];
                        }
                        if (! activeWeek && ! item.home_score && ! item.away_score)
                        {
                            activeWeek = week;
                        }

                        groupedData[week].push(item);
                    });

                    this.setState({fixture: groupedData, activeWeek: activeWeek});

                    return groupedData;
                })
                .catch(error => {
                    console.error(error);
                });
        };

        getChampionship = () => {
            return fetch('/api/championship')
                .then(response => response.json())
                .then(json => {
                    this.setState({championship: json.data});
                    return json.data;
                })
                .catch(error => {
                    console.error(error);
                });
        };

        getStanding = () => {
            return fetch('/api/standing')
                .then(response => response.json())
                .then(json => {
                    this.setState({standings: json.data});
                    return json.data;
                })
                .catch(error => {
                    console.error(error);
                });
        };

        componentDidMount() {
            this.getTeams()

            this.rebuildAllData()
        }

        rebuildAllData() {
            this.getChampionship()
            this.getStanding()
            this.getFixture()
        }

        async resetLeague(e) {
            fetch('/api/fixture/reset')
                .then(response => response.json())
                .then(data => {
                    this.rebuildAllData();
                })
                .catch(error => {
                    console.error(error);
                });
        }

        async playWeek(e) {
            fetch('/api/fixture/play/' + this.state.activeWeek)
                .then(response => response.json())
                .then(data => {
                    this.rebuildAllData();
                })
                .catch(error => {
                    console.error(error);
                });
        }

        async playAll(e) {
            fetch('/api/fixture/play/all')
                .then(response => response.json())
                .then(data => {
                    this.rebuildAllData();
                })
                .catch(error => {
                    console.error(error);
                });
        }

        render() {
            return (
                <div>
                    <div className="container">
                        <div className="row">
                            <div className="col-lg">

                                <button
                                    type="button"
                                    className="btn btn-primary"
                                    onClick={this.playWeek}
                                    disabled={this.state.activeWeek === 0}
                                >Play Next Week
                                </button>

                                <button
                                    type="button"
                                    className="btn btn-secondary"
                                    disabled={this.state.activeWeek === 0}
                                    onClick={this.playAll}
                                >Play All Weeks
                                </button>

                                <button
                                    type="button"
                                    className="btn btn-danger"
                                    onClick={this.resetLeague}
                                >Reset Data
                                </button>

                                {
                                    this.state.fixture.length !== 0 ? (
                                        <div className="container">
                                            <h2>Fixture</h2>
                                            <div className="row">
                                                {
                                                    this.state.fixture.map((matchesOnWeek, weekIndex) =>
                                                        <div key={weekIndex} className="col-md-4">
                                                            <table className="table">
                                                                <thead className="thead-dark">
                                                                <tr>
                                                                    <th>Home</th>
                                                                    <th>Away</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                {
                                                                    matchesOnWeek.map((match, matchIndex) =>
                                                                        <tr key={matchIndex}>
                                                                            <td>{ this.state.teams[match.home_id] } - {match.home_score ? match.home_score : ''} </td>
                                                                            <td>{ this.state.teams[match.away_id] } - {match.away_score ? match.away_score : ''}</td>
                                                                        </tr>
                                                                    )
                                                                }
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    )
                                                }
                                            </div>
                                        </div>
                                    ) : (
                                        <div>
                                            <h3>No results to show for fixture</h3>
                                        </div>
                                    )
                                }

                                <br/>
                                <br/>

                                <div className="container">
                                    <h2>Fixture</h2>
                                    <div className="row">
                                    {
                                        this.state.championship.length !== 0 ? (
                                            <div className="col-md-6">
                                                <h2>Championship</h2>
                                                    <table className="table">
                                                        <thead className="thead-dark">
                                                        <tr>
                                                            <th>Team</th>
                                                            <th>Rate</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        {

                                                            this.state.championship.map((team, i) =>
                                                                <tr key={i}>
                                                                    <td>{ this.state.teams[team.team_id] }</td>
                                                                    <td>{ team.prediction }</td>
                                                                </tr>

                                                            )
                                                        }
                                                        </tbody>
                                                    </table>
                                            </div>
                                        ) : (
                                            <div>
                                                <h3>No results to show for championship</h3>
                                            </div>
                                        )
                                    }

                                    {
                                        this.state.standings.length !== 0 ? (
                                            <div className="col-md-6">
                                                <h2>Standing</h2>
                                                    <table className="table">
                                                        <thead className="thead-dark">
                                                        <tr>
                                                            <th>Team</th>
                                                            <th>Played</th>
                                                            <th>P</th>
                                                            <th>W</th>
                                                            <th>D</th>
                                                            <th>L</th>
                                                            <th>GD</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        {

                                                            this.state.standings.map((team, i) =>
                                                                <tr key={i}>
                                                                    <td>{ this.state.teams[team.team_id] }</td>
                                                                    <td>{ team.played }</td>
                                                                    <td>{ team.points }</td>
                                                                    <td>{ team.wins }</td>
                                                                    <td>{ team.draws }</td>
                                                                    <td>{ team.losses }</td>
                                                                    <td>{ team.goal_difference }</td>
                                                                </tr>

                                                            )
                                                        }
                                                        </tbody>
                                                    </table>
                                            </div>
                                        ) : (
                                            <div>
                                                <h3>No results to show for standings</h3>
                                            </div>
                                        )
                                    }
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            );
        }
    }

    ReactDOM.render(
        <App/>,
        document.getElementById('app')
    );
</script>

</body>
</html>
