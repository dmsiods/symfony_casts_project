// The svg
const svg = d3.select('svg');
const color = ['grey', 'blue', 'light-grey'];

function line(svg, {x1, y1, x2, y2, color}) {
  svg.append('line')
    .attr('x1', x1)
    .attr('y1', y1)
    .attr('x2', x2)
    .attr('y2', y2)
    .style('stroke', color)
    .style('stroke-width', 5)
  ;
}

function rect(svg, {x1, y1, x2, y2, color}) {
  svg.append('rect')
    .attr('x', x1)
    .attr('y', y1)
    .attr('width', Math.abs(x2 - x1))
    .attr('height', Math.abs(y2 - y1))
    .style('fill', 'light' + color)
    .attr('transform', `scale(4,2)`)
  ;
}

// function path(svg, {x1, y1, x2, y2}) {
//   svg.append('path')
//     .attr('d', `M ${x1} ${y1} L ${x1} ${y2} L ${x2} ${y2} Z`)
//     .attr('fill', `blue`)
//   ;
// }

// worldMap(svg, '/world.json');
async function worldMap(svg, path) {
  const width = +svg.attr('width');
  const height = +svg.attr('height');

  // Map and projection
  const projection = d3.geoNaturalEarth1()
    .scale(width / 1.5 / Math.PI)
    .rotate([0, 0])
    .center([0, 0])
    .translate([width / 2, height / 2])

  // Load external data and boot
  let data = await d3.json(path);

  // Draw the map
  svg.append('g')
    .selectAll("path")
    .data(data.features)
    .join('path')
    .attr('fill', '#69b3a2')
    .attr('d', d3.geoPath().projection(projection))
    .style('stroke', '#fff')
}
