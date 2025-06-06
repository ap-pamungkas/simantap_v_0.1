

 <div class="menu-item {{ request()->is($url) ? 'active' : '' }}">
        <a href="{{ url($url) }}" class="menu-link">
          <span class="menu-icon">
            <i class="{{ $icon }}"></i> <!-- Font Awesome Icon -->
          </span>
          <span class="menu-text">{{ $label }}</span>
        </a>
      </div>
      <br>
