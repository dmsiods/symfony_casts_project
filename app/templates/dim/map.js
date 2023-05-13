const options = {
  linear: {
    minX: Number.MAX_VALUE,
    minY: Number.MAX_VALUE,
    maxX: -Number.MAX_VALUE,
    maxY: -Number.MAX_VALUE,
  },
  K: 20,
  minLong: -30,
  maxLong: 55,
  minLat: 20,
  maxLat: 71,
};

dataLoad()
  .then((data) => drawMap({
    svg,
    data,
    options,
    // proectionFn: proectionMiller
    proectionFn: proectionMerkator
  }))

// ================================

async function dataLoad(){

  let data = [];
  let file;

  file = await d3.json('/Poland.json');
  options.K = 50
  data.push(...file);
  //
  file = await d3.json('/Belarus.json');
  data.push(...file);

  return data;
}

async function drawMap({ svg, data, options, proectionFn }) {

  // XYZ converter & min-max
  data.forEach((item) => {

    if (item.geometry?.type === 'Polygon') {
      let coords = item.geometry?.coordinates[0];
      coords = coords.filter((item) => pointFilter(item, options));
      coords.forEach((point) => (proectionFn(point, options)));
      options.linear = minmax(coords, options.linear);
    }

    if (item.geometry?.type === 'MultiPolygon') {
      const mCords = item.geometry?.coordinates;
      mCords.forEach((coords) => {
        coords[0] = coords[0].filter((item) => pointFilter(item, options));
        coords[0].forEach((point) => (proectionFn(point, options)));
        options.linear = minmax(coords[0], options.linear);
      });
    }
  });

  const limitX = (options.linear.maxX - options.linear.minX);
  const limitY = (options.linear.maxY - options.linear.minY);
  svg.attr('viewBox', `0 0 ${limitX * options.K} ${limitY * options.K}`);


  data.forEach((item) => {
    let coords = item.geometry?.coordinates[0];
    if (item.geometry?.type === 'Polygon') drawCountry(svg, coords, options);
    if (item.geometry?.type === 'MultiPolygon') {
      const mCords = item.geometry?.coordinates;
      mCords.forEach((coords) => (drawCountry(svg, coords[0], options)));
    }
  });
}

function drawCountry(svg, coords, options) {
  if (!Array.isArray(coords) || coords.length <= 0) return;
  let path = 'M';
  coords.forEach((point, i) => {
    const _p = pointConv(point, options);
    if (i > 0) path = `${path} L `;
    path = path + `${_p[0]} ${_p[1]}`;
  });
  path = path + 'Z';
  svg.append('path')
    .attr('_type', 'country')
    .attr('d', path)
  ;
}

function pointFilter(point, options) {
  if ((point[0] < options.minLong) && (point[1] < options.minLat)) return null;
  if ((point[0] < options.minLong) && (point[1] > options.maxLat)) return null;
  if ((point[0] > options.maxLong) && (point[1] < options.minLat)) return null;
  if ((point[0] > options.maxLong) && (point[1] > options.maxLat)) return null;

  if ((point[0] < options.minLong)) point[0] = options.minLong;
  if ((point[0] > options.maxLong)) point[0] = options.maxLong;
  if ((point[1] < options.minLat)) point[1] = options.minLat;
  if ((point[1] > options.maxLat)) point[1] = options.maxLat;
  return point;
}

function minmax(coords, options) {
  if (!Array.isArray(coords) || coords.length <= 0) return options;

  coords.forEach((point) => {
    if (options.minX > point[0]) options.minX = point[0];
    if (options.minY > point[1]) options.minY = point[1];
    if (options.maxX < point[0]) options.maxX = point[0];
    if (options.maxY < point[1]) options.maxY = point[1];
  });
  return options;
}

function proectionMerkator(point, options){
  const degL = point[0]; // Longitude
  const degB = point[1]; // Latitude
  const radL = degreeToRadian(degL);
  const radB = degreeToRadian(degB);

  const X = options.K * radL;
  const Y = options.K * Math.atanh(Math.sin(radB));

  point[0] = X;
  point[1] = Y;
  return point;
}

function proectionMiller(point, options){

  const degL = point[0]; // Longitude
  const degB = point[1]; // Latitude

  const radL = degreeToRadian(degL);
  const radB = degreeToRadian(degB);

  const X = options.K * radL;

  const tan = Math.tan(Math.PI / 4 + 2 / 5 * radB);
  const Y = options.K * ( 5 / 4 ) * Math.log(tan);

  point[0] = X;
  point[1] = Y;
  return point;
}

function pointConv(point, options){
  const x = +((point[0] - options.linear.minX) * options.K).toFixed(0);
  const y = +((options.linear.maxY - point[1]) * options.K).toFixed(0);
  return [x, y];
}

function degreeToRadian(value) {
  return value * Math.PI / 180;
};




