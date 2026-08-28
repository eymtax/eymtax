const crypto = require('crypto');

exports.handler = async (event) => {
  const secret = process.env.SESSION_SECRET;
  const cookieHeader = event.headers.cookie || event.headers.Cookie || '';
  const match = cookieHeader.match(/(?:^|;\s*)admin_session=([^;]+)/);

  if (!secret || !match) {
    return { statusCode: 401, body: JSON.stringify({ authenticated: false }) };
  }

  const [expires, signature] = decodeURIComponent(match[1]).split('.');
  if (!expires || !signature) {
    return { statusCode: 401, body: JSON.stringify({ authenticated: false }) };
  }

  const expectedSignature = crypto.createHmac('sha256', secret).update(expires).digest('hex');
  const sigBuf = Buffer.from(signature);
  const expBuf = Buffer.from(expectedSignature);
  const validSignature = sigBuf.length === expBuf.length && crypto.timingSafeEqual(sigBuf, expBuf);

  if (!validSignature || Date.now() > Number(expires)) {
    return { statusCode: 401, body: JSON.stringify({ authenticated: false }) };
  }

  return { statusCode: 200, body: JSON.stringify({ authenticated: true }) };
};
