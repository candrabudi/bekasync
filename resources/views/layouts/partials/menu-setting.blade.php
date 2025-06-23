<style>
    .settings-menu {
        display: flex;
        gap: 1rem;
        background: #f1f5f9; /* abu muda atau sesuai tema */
        padding: 0.5rem;
        border-radius: 0.75rem;
    }

    .settings-menu a {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        color: #1f2937;
        text-decoration: none;
        transition: 0.2s ease;
    }

    .settings-menu a:hover {
        background-color: #e2e8f0;
    }

    .settings-menu a.active {
        background-color: #ffffff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        font-weight: 600;
    }
</style>

<div class="settings-menu">
    <a href="{{ route('government_units.index') }}">Data OPD</a>
    <a href="{{ route('agencies.index') }}">User OPD</a>
    <a href="{{ route('users.index') }}">Users</a>
</div>
