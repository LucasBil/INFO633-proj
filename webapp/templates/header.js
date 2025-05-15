const api = new API();
const cookieManager = new CookiesManager();
const profile = JSON.parse(cookieManager.getCookie('user'));

function roleGranted(roles) {
  if (!profile || !Array.isArray(profile.roles)) return false;

  return roles.some(allowedRole =>
    profile.roles.some(userRole =>
      allowedRole.includes(userRole)
    )
  );
}