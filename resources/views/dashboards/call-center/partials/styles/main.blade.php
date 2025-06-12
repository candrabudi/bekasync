<style>
    .settings-menu {
        display: flex;
        gap: 0.2rem;
        background: #00b894;
        padding: 0.5rem;
        border-radius: 0.75rem;
    }

    .settings-menu a {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        color: #1f2937;
        text-decoration: none;
        transition: 0.2s ease;
        font-weight: 600;
    }

    .settings-menu a:hover {
        background-color: #ffffff;
        font-weight: 600;
    }

    .settings-menu a.active {
        background-color: #ffffff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        font-weight: 600;
    }
</style>

<style>
    .enhanced-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        padding-bottom: 1rem;
        /* border-bottom: 1px solid #e0e0e0; */
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .trend-stats {
        text-align: right;
        font-size: 0.875rem;
        color: #5f6368;
    }

    .filter-dropdown {
        padding: 0.4rem 0.75rem;
        border-radius: 8px;
        border: 1px solid #dadce0;
        font-size: 0.9rem;
        background: #fff;
        color: #202124;
        outline: none;
        transition: border-color 0.3s ease;
    }

    .filter-dropdown:focus {
        border-color: #1a73e8;
        box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.1);
    }

    .skeleton {
        display: inline-block;
        height: 1.2em;
        width: 3em;
        background: linear-gradient(90deg, #eee 25%, #ddd 50%, #eee 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 4px;
    }

    @keyframes shimmer {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }
</style>
