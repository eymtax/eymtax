const crypto = require('crypto');

function safeEqual(a, b) {
  const bufA = Buffer.from(String(a));
  const bufB = Buffer.from(String(b));
  if (bufA.length !== bufB.length) return false;
  return crypto.timingSafeEqual(bufA, bufB);
}

exports.handler = async (event) => {
  if (event.httpMethod !== 'POST') {
    return { statusCode: 405, body: JSON.stringify({ error: 'method_not_allowed' }) };
  }

  const expectedUser = process.env.ADMIN_USERNAME;
  const expectedPass = process.env.ADMIN_PASSWORD;
  const secret = process.env.SESSION_SECRET;

  if (!expectedUser || !expectedPass || !secret) {
    return { statusCode: 500, body: JSON.stringify({ error: 'server_not_configured' }) };
  }

  let username, password;
  try {
    ({ username, password } = JSON.parse(event.body || '{}'));
  } catch {
    return { statusCode: 400, body: JSON.stringify({ error: 'invalid_request' }) };
  }

  if (
    typeof username !== 'string' ||
    typeof password !== 'string' ||
    !safeEqual(username, expectedUser) ||
    !safeEqual(password, expectedPass)
  ) {
    return { statusCode: 401, body: JSON.stringify({ error: 'invalid_credentials' }) };
  }

  const expires = Date.now() + 8 * 60 * 60 * 1000; // 8 hours
  const signature = crypto.createHmac('sha256', secret).update(String(expires)).digest('hex');
  const token = `${expires}.${signature}`;

  return {
    statusCode: 200,
    headers: {
      'Content-Type': 'application/json',
      'Set-Cookie': `admin_session=${token}; Path=/; HttpOnly; Secure; SameSite=Strict; Max-Age=28800`,
    },
    body: JSON.stringify({ ok: true }),
  };
};
