function encode(e) {
    function t(e, t) {
        return e << t | e >>> 32 - t
    }
    function n(e, t) {
        var n = 2147483648 & e,
            r = 2147483648 & t,
            o = 1073741824 & e,
            a = 1073741824 & t,
            i = (1073741823 & e) + (1073741823 & t);
        return o & a ? 2147483648 ^ i ^ n ^ r: o | a ? 1073741824 & i ? 3221225472 ^ i ^ n ^ r: 1073741824 ^ i ^ n ^ r: i ^ n ^ r
    }
    function r(e, t, n) {
        return e & t | ~e & n
    }
    function o(e, t, n) {
        return e & n | t & ~n
    }
    function a(e, t, n) {
        return e ^ t ^ n
    }
    function i(e, t, n) {
        return t ^ (e | ~n)
    }
    function u(e, o, a, i, u, l, s) {
        return e = n(e, n(n(r(o, a, i), u), s)),
            n(t(e, l), o)
    }
    function l(e, r, a, i, u, l, s) {
        return e = n(e, n(n(o(r, a, i), u), s)),
            n(t(e, l), r)
    }
    function s(e, r, o, i, u, l, s) {
        return e = n(e, n(n(a(r, o, i), u), s)),
            n(t(e, l), r)
    }
    function c(e, r, o, a, u, l, s) {
        return e = n(e, n(n(i(r, o, a), u), s)),
            n(t(e, l), r)
    }
    function f(e) {
        var t = "",
            n = "",
            r = void 0,
            o = void 0;
        for (o = 0; o <= 3; o++) r = e >>> 8 * o & 255,
            n = "0" + r.toString(16),
            t += n.substr(n.length - 2, 2);
        return t
    }
    var d = [],
        p = void 0,
        h = void 0,
        m = void 0,
        v = void 0,
        y = void 0,
        b = void 0,
        g = void 0,
        w = void 0,
        _ = void 0;
    for (e = function(e) {
        e = e.replace(/\r\n/g, "\n");
        for (var t = "",
                 n = 0; n < e.length; n++) {
            var r = e.charCodeAt(n);
            r < 128 ? t += String.fromCharCode(r) : r > 127 && r < 2048 ? (t += String.fromCharCode(r >> 6 | 192), t += String.fromCharCode(63 & r | 128)) : (t += String.fromCharCode(r >> 12 | 224), t += String.fromCharCode(r >> 6 & 63 | 128), t += String.fromCharCode(63 & r | 128))
        }
        return t
    } (e), d = function(e) {
        for (var t = void 0,
                 n = e.length,
                 r = n + 8,
                 o = (r - r % 64) / 64, a = 16 * (o + 1), i = new Array(a - 1), u = 0, l = 0; l < n;) t = (l - l % 4) / 4,
            u = l % 4 * 8,
            i[t] |= e.charCodeAt(l) << u,
            l += 1;
        return t = (l - l % 4) / 4,
            u = l % 4 * 8,
            i[t] |= 128 << u,
            i[a - 2] = n << 3,
            i[a - 1] = n >>> 29,
            i
    } (e), b = 1732584193, g = 4023233417, w = 2562383102, _ = 271733878, p = 0; p < d.length; p += 16) h = b,
        m = g,
        v = w,
        y = _,
        b = u(b, g, w, _, d[p + 0], 7, 3614090360),
        _ = u(_, b, g, w, d[p + 1], 12, 3905402710),
        w = u(w, _, b, g, d[p + 2], 17, 606105819),
        g = u(g, w, _, b, d[p + 3], 22, 3250441966),
        b = u(b, g, w, _, d[p + 4], 7, 4118548399),
        _ = u(_, b, g, w, d[p + 5], 12, 1200080426),
        w = u(w, _, b, g, d[p + 6], 17, 2821735955),
        g = u(g, w, _, b, d[p + 7], 22, 4249261313),
        b = u(b, g, w, _, d[p + 8], 7, 1770035416),
        _ = u(_, b, g, w, d[p + 9], 12, 2336552879),
        w = u(w, _, b, g, d[p + 10], 17, 4294925233),
        g = u(g, w, _, b, d[p + 11], 22, 2304563134),
        b = u(b, g, w, _, d[p + 12], 7, 1804603682),
        _ = u(_, b, g, w, d[p + 13], 12, 4254626195),
        w = u(w, _, b, g, d[p + 14], 17, 2792965006),
        g = u(g, w, _, b, d[p + 15], 22, 1236535329),
        b = l(b, g, w, _, d[p + 1], 5, 4129170786),
        _ = l(_, b, g, w, d[p + 6], 9, 3225465664),
        w = l(w, _, b, g, d[p + 11], 14, 643717713),
        g = l(g, w, _, b, d[p + 0], 20, 3921069994),
        b = l(b, g, w, _, d[p + 5], 5, 3593408605),
        _ = l(_, b, g, w, d[p + 10], 9, 38016083),
        w = l(w, _, b, g, d[p + 15], 14, 3634488961),
        g = l(g, w, _, b, d[p + 4], 20, 3889429448),
        b = l(b, g, w, _, d[p + 9], 5, 568446438),
        _ = l(_, b, g, w, d[p + 14], 9, 3275163606),
        w = l(w, _, b, g, d[p + 3], 14, 4107603335),
        g = l(g, w, _, b, d[p + 8], 20, 1163531501),
        b = l(b, g, w, _, d[p + 13], 5, 2850285829),
        _ = l(_, b, g, w, d[p + 2], 9, 4243563512),
        w = l(w, _, b, g, d[p + 7], 14, 1735328473),
        g = l(g, w, _, b, d[p + 12], 20, 2368359562),
        b = s(b, g, w, _, d[p + 5], 4, 4294588738),
        _ = s(_, b, g, w, d[p + 8], 11, 2272392833),
        w = s(w, _, b, g, d[p + 11], 16, 1839030562),
        g = s(g, w, _, b, d[p + 14], 23, 4259657740),
        b = s(b, g, w, _, d[p + 1], 4, 2763975236),
        _ = s(_, b, g, w, d[p + 4], 11, 1272893353),
        w = s(w, _, b, g, d[p + 7], 16, 4139469664),
        g = s(g, w, _, b, d[p + 10], 23, 3200236656),
        b = s(b, g, w, _, d[p + 13], 4, 681279174),
        _ = s(_, b, g, w, d[p + 0], 11, 3936430074),
        w = s(w, _, b, g, d[p + 3], 16, 3572445317),
        g = s(g, w, _, b, d[p + 6], 23, 76029189),
        b = s(b, g, w, _, d[p + 9], 4, 3654602809),
        _ = s(_, b, g, w, d[p + 12], 11, 3873151461),
        w = s(w, _, b, g, d[p + 15], 16, 530742520),
        g = s(g, w, _, b, d[p + 2], 23, 3299628645),
        b = c(b, g, w, _, d[p + 0], 6, 4096336452),
        _ = c(_, b, g, w, d[p + 7], 10, 1126891415),
        w = c(w, _, b, g, d[p + 14], 15, 2878612391),
        g = c(g, w, _, b, d[p + 5], 21, 4237533241),
        b = c(b, g, w, _, d[p + 12], 6, 1700485571),
        _ = c(_, b, g, w, d[p + 3], 10, 2399980690),
        w = c(w, _, b, g, d[p + 10], 15, 4293915773),
        g = c(g, w, _, b, d[p + 1], 21, 2240044497),
        b = c(b, g, w, _, d[p + 8], 6, 1873313359),
        _ = c(_, b, g, w, d[p + 15], 10, 4264355552),
        w = c(w, _, b, g, d[p + 6], 15, 2734768916),
        g = c(g, w, _, b, d[p + 13], 21, 1309151649),
        b = c(b, g, w, _, d[p + 4], 6, 4149444226),
        _ = c(_, b, g, w, d[p + 11], 10, 3174756917),
        w = c(w, _, b, g, d[p + 2], 15, 718787259),
        g = c(g, w, _, b, d[p + 9], 21, 3951481745),
        b = n(b, h),
        g = n(g, m),
        w = n(w, v),
        _ = n(_, y);
    return (f(b) + f(g) + f(w) + f(_)).toLowerCase()
}