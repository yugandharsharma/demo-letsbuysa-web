@include('includes-file.header')
@include('includes-file.sidebar')
@php
    $permission = permission();
@endphp
      <!--Start Dashboard Content-->
  <div class="clearfix"></div>
  <div class="content-wrapper">
    <div class="container-fluid">
      <div class="row mt-3">
        
        <div class="col-6 col-lg-6 col-xl-6 mb-5">
          <div class="col-12 col-lg-12 col-xl-12">
            <section id="auth-button"></section>  
          </div>
        </div>
        
        <div class="col-6 col-lg-6 col-xl-6 mb-5">
          <div class="col-12 col-lg-12 col-xl-12">
            <section id="view-selector"></section>
          </div>
        </div>
        
        <br>
        <br>

        <div class="col-6 col-lg-6 col-xl-6 mb-5">
          <div class="col-6 col-lg-6 col-xl-6">
            <section id="is_session"></section>
          </div>
        </div>

        <div class="col-6 col-lg-6 col-xl-6 mb-5">
          <div class="col-6 col-lg-6 col-xl-6">
            <section id="is_users"></section>
          </div>
        </div>

        <br>
        <br>

        <div class="col-6 col-lg-6 col-xl-6 mb-5">
          <div class="col-6 col-lg-6 col-xl-6">
            <section id="is_page_view"></section>
          </div>
        </div>

        <div class="col-6 col-lg-6 col-xl-6 mb-5">
          <div class="col-6 col-lg-6 col-xl-6">
            <section id="is_hits"></section>
          </div>
        </div>

      </div>
    </div>
  </div>

@include('includes-file.footer')

<script>
(function(w,d,s,g,js,fjs){
  g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(cb){this.q.push(cb)}};
  js=d.createElement(s);fjs=d.getElementsByTagName(s)[0];
  js.src='https://apis.google.com/js/platform.js';
  fjs.parentNode.insertBefore(js,fjs);js.onload=function(){g.load('analytics')};
}(window,document,'script'));
</script>
<script>
gapi.analytics.ready(function() {

  // Step 3: Authorize the user.

  var CLIENT_ID = '843400075916-hcbplvldqtsqm73hd7mljhqt9pa0obk3.apps.googleusercontent.com';

  gapi.analytics.auth.authorize({
    container: 'auth-button',
    clientid: CLIENT_ID,
  });

  // Step 4: Create the view selector.

  var viewSelector = new gapi.analytics.ViewSelector({
    container: 'view-selector'
  });

  /**
   * ga:sessions
      ga:users
      ga:pageviews
      ga:bounceRate
      ga:hits
      ga:avgSessionDuration
      active_users
   * */
   //Session
  var isSession = new gapi.analytics.googleCharts.DataChart({
    reportType: 'ga',
    query: {
      'dimensions': 'ga:date',
      'metrics': 'ga:sessions',
      'start-date': '90daysAgo',
      'end-date': 'yesterday',
    },
    chart: {
      type: 'LINE',
      container: 'is_session'
    }
  });

  var isUsers = new gapi.analytics.googleCharts.DataChart({
    reportType: 'ga',
    query: {
      'dimensions': 'ga:date',
      'metrics': 'ga:users',
      'start-date': '90daysAgo',
      'end-date': 'yesterday',
    },
    chart: {
      type: 'LINE',
      container: 'is_users'
    }
  });


  var isPageView = new gapi.analytics.googleCharts.DataChart({
    reportType: 'ga',
    query: {
      'dimensions': 'ga:date',
      'metrics': 'ga:pageviews',
      'start-date': '90daysAgo',
      'end-date': 'yesterday',
    },
    chart: {
      type: 'LINE',
      container: 'is_page_view'
    }
  });

  var isHits = new gapi.analytics.googleCharts.DataChart({
      reportType: 'ga',
      query: {
        'dimensions': 'ga:date',
        'metrics': 'ga:hits',
        'start-date': '90daysAgo',
        'end-date': 'yesterday',
      },
      chart: {
        type: 'LINE',
        container: 'is_hits'
      }
    });

  // Step 6: Hook up the components to work together.

  gapi.analytics.auth.on('success', function(response) {
    viewSelector.execute();
  });

  viewSelector.on('change', function(ids) {
    var newIds = {
      query: {
        ids: ids
      }
    }
    isSession.set(newIds).execute();
    isUsers.set(newIds).execute();
    isPageView.set(newIds).execute();
    isHits.set(newIds).execute();
  });
});
</script>