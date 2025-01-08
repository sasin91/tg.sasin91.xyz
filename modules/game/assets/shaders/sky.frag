uniform samplerCube background;
uniform samplerCube stars;
uniform float time;

varying vec3 vWorldPosition;

void main() {
    vec3 viewDirection = normalize(vWorldPosition - cameraPosition);
    vec3 sunDirection = normalize(vec3(0.0, 0.25, 1.0));
    vec3 sky = textureCube(background, viewDirection).xyz;

    float c1 = cos(time * 0.02);
    float s1 = sin(time * 0.02);
    float c2 = cos(time * 0.0075);
    float s2 = sin(time * 0.0075);
    mat3 r1 = mat3(
    1.0, 0.0, 0.0,
    0.0, c1, -s1,
    0, s1, c1);
    mat3 r2 = mat3(
    c2, 0.0, s2,
    0.0, 1.0, 0.0,
    -s2, 0.0, c2);
    vec3 stars = textureCube(stars, r1 * r2 * viewDirection).xyz;

    sky = pow(sky, vec3(1.5, 1.5, 1.2));

    vec3 luma = vec3( 0.299, 0.587, 0.114 );
    float starAlpha = clamp(dot(sky, luma) + 0.5, 0.0, 1.0);
    starAlpha = pow(starAlpha, 1.5);
    sky = mix(stars, sky, starAlpha);

    float bloom = 0.2 * pow(max(0.0, dot(viewDirection, sunDirection)), 16.0);

    gl_FragColor = vec4(sky * (1.0 - bloom * 2.0), bloom);
}
