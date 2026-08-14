@extends('layouts.gentelella')

@section('title', 'Dashboard')
@section('page', 'dashboard')
@section('breadcrumb', 'Home > Dashboard')

@section('content')
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <div class="page-pretitle">Overview</div>
                <h1 class="page-title">Dashboard</h1>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-outline">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 2v12M2 8h12"/></svg>
                    New view
                </button>
                <button type="button" class="btn btn-primary">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 8h8M8 4v8"/></svg>
                    Create report
                </button>
            </div>
        </div>
    </div>

    <div class="row col-3">
        <div class="card">
            <div class="stat">
                <div class="stat-icon teal">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Total Users</div>
                    <div class="stat-value-row">
                        <span class="stat-value">2,500</span>
                        <span class="stat-change up">12%</span>
                    </div>
                    <div class="stat-subtext">342 new this week</div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="stat">
                <div class="stat-icon blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Avg Session</div>
                    <div class="stat-value-row">
                        <span class="stat-value">123.5<span style="font-size:12px;font-weight:400;color:var(--text-muted);margin-left:1px">min</span></span>
                        <span class="stat-change up">8%</span>
                    </div>
                    <div class="stat-subtext">+14min from last week</div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="stat">
                <div class="stat-icon yellow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/></svg>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Orders</div>
                    <div class="stat-value-row">
                        <span class="stat-value">1,240</span>
                        <span class="stat-change down">3%</span>
                    </div>
                    <div class="stat-subtext">18 pending fulfillment</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row col-2" style="margin-top:var(--gap)">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Revenue</h3>
            </div>
            <div class="card-body">
                <div data-chart="revenue-line" style="width:100%;height:300px"></div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Traffic sources</h3>
            </div>
            <div class="card-body">
                <div data-chart="traffic-donut" style="width:100%;height:300px"></div>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top:var(--gap)">
        <div class="card-header">
            <h3 class="card-title">Recent activity</h3>
        </div>
        <div class="card-body">
            <table class="table" data-datatable data-page-length="5">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Jane Cooper</td>
                        <td>Created invoice #1024</td>
                        <td><span class="status success">Completed</span></td>
                        <td>2026-08-14</td>
                    </tr>
                    <tr>
                        <td>Robert Fox</td>
                        <td>Updated profile settings</td>
                        <td><span class="status info">In progress</span></td>
                        <td>2026-08-14</td>
                    </tr>
                    <tr>
                        <td>Esther Howard</td>
                        <td>Submitted support ticket</td>
                        <td><span class="status warning">Pending</span></td>
                        <td>2026-08-13</td>
                    </tr>
                    <tr>
                        <td>Cameron Williamson</td>
                        <td>Exported monthly report</td>
                        <td><span class="status success">Completed</span></td>
                        <td>2026-08-13</td>
                    </tr>
                    <tr>
                        <td>Brooklyn Simmons</td>
                        <td>Changed subscription plan</td>
                        <td><span class="status danger">Failed</span></td>
                        <td>2026-08-12</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
