# ILIAS-Charts

## Interface Definition
- **Chart:** Contains basic functionality for all charts. Do not interact with this. (Extend **ChartWithBackground** or **ChartWithoutBackground** instead)
- **ChartWithBackground:** Contains basic functionality for charts with a background. Extend this if your chart contains a background.
- **ChartWithoutBackground:** Contains basic functionality for charts without a background. Extend if your chart doesn't contain a background.
- **ChartBackground:** A background consists of comparison points/paths. e.g. a column diagram has lines in the background of the columns, each assigned to a value. The columns can be compared to them. While the top comparison line might have a value of 1'000'000, the bottom one usually has a value of 0. A pie chart does not have a background.
- **ChartComparisonPath:** Comparison paths are used to compare diagram values. They represent background lines in a column diagram, while they represent a polygon in a spider diagram.
- **ChartItem:** Represents a value in a chart. e.g. they represent a single section in a pie chart diagram while they represent a whole line in a line diagram. A pie chart only uses a single value per item while a line diagram contains multiple values per line. (Goes up and down)
- **ChartLabel:** Basically just a label with a color, coordinates, font size and text.
- **ChartLegend:** A typical legend containing legend entries.
- **ChartLegendEntry:** Shows the user which color belongs to which title.
- **ChartPoint:** A basic coordinate object.

## How To Implement A New Chart?

**Note: Rules of src\UI\README.md still apply**

If you would like to implement a new chart you should perform the following tasks:

1. Determine if your chart has a background or not. A background consists of comparison points/paths. e.g. a column diagram has lines in the background of the columns, each assigned to a value. The columns can be compared to them. While the top comparison line might have a value of 1'000'000, the bottom one usually has a value of 0. For instance a pie chart does not have a background.
2. Create a new interface of your chart and extend the methods of either the interface **ChartWithBackground** or **ChartWithoutBackground**, based on the result of task 1.
3. Both choices require you to at least implement the following functionality:
   - Legend with entries
     - Basic legend functionality
     - Ability to hide legend
     - Legend entry text color customization
   - Chart items
     - Basic chart item functionality

   If you have a background you also need to implement the following:
   - Background
     - Basic background functionality
     - Comparison path color customization
     - Comparison path label color customization
     
   Chart-specific methods can be created on your newly set up interface. e.g. If you want a custom legend create a new interface, extend ChartLegend and write your own methods in there.
   
   ``` php
    ----- Implemented from Chart interface -----
    /**
     * @param Color $color
     *
     * @return self
     */
    public function withCustomLegendTextColor(Color $color): Chart;
   
   ----- Chart-specific method -----
    /**
     * @param Color $color
     *
     * @return PieChart
     */
    public function withCustomSectionLabelColor(Color $color): self;
   ```
4. Create your classes by implementing either your custom interface (if available) or a predefined one.
   
5. When creating the template for your chart make sure it's based on SVG, works based on percentages and uses SVG elements. (text, circle, rect, ...)

	``` php
    <svg viewBox="...">
    	...
    </svg>
    ```
6. As PHP doesn't support generics (yet) return types of interfaces are quite cumbersome to work with. To make the auto-completion work with extended methods from the Chart, ChartWithBackground and ChartWithoutBackground interfaces, it is suggested you do the following:

   In your main chart interface copy and paste all extended withX() methods and methods which return an extended interface for which you have made a custom interface, and...
   
   ...change every extended **withX()** method by changing the return type to **self** (in PHPDoc)
   
   ``` php
   /**
     * @param Color $color
     *
     * @return --->self<---
     */
    public function withCustomLegendTextColor(Color $color): Chart;
   ```
   
   ...change every extended **getX()** for which you have made a custom interface method by changing the return type to **your custom Interface** (in PHPDoc, e.g. PieChartLegend instead of ChartLegend)
   
   ``` php
    /**
      * @return --->PieChartLegend<---
      */
      public function getLegend(): ChartLegend;
   ```
## Help
If you're having trouble understanding take a look at the PieChart implementation.
   

