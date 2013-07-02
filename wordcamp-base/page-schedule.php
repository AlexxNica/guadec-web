<?php
/*
Template Name: Schedule
*/

get_header(); ?>

<div id="container">
	<div id="content" role="main">
	    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <?php the_content(); ?>
        <?php endwhile; endif; ?>
	
	    <div class="schedule-nav">	        
    	    <a href="#day1">Day 1</a> | <a href="#day2">Day 2</a> | <a href="#day3">Day 3</a> | <a href="#day4">Day 4</a>
        </div>

        <h3 id="day1" class="sub-section-title">Day 1: Thursday, August 1st</h3>

        <table class="schedule">
            <tr>
                <td></td>
                <td class="room-name">Room 1</td>
                <td class="room-name">Room 2</td>
            </tr>
        
            <tr>
                <td>09:00 - 10:00</td>
                <td class="keynote" colspan="2">Keynote @ Room 3</td>
            </tr>
            <tr>
                <td>10:00 - 10:40</td>
                <td class="break" colspan="2">Morning break</td>
            </tr>
            <tr>
                <td>10:40 - 11:25</td>
                <td>The Future of GNOME 3 <span class="speaker-name">Allan Day</span></td>
                <td>News from the GNOME OSTree project <span class="speaker-name">Colin Walters</span</td>
            </tr>
            <tr>
                <td>11:35 - 12:20</td>
                <td>First steps towards contributing <span class="speaker-name">Ekaterina Gerasimova</span</td>
                <td>Web, the future is now <span class="speaker-name">Claudio Saavedra</span</td>
            </tr>
            <tr>
                <td>12:30 - 14:00</td>
                <td class="lunch" colspan="2">Lunch</td>
            </tr>
            <tr>
                <td>14:00 - 14:45</td>
                <td>How to not report your UX bug <span class="speaker-name">Fabiana Simões</span</td>
                <td>LibreOffice and GNOME <span class="speaker-name">Michael Meeks</span</td>
            </tr>
            <tr>
                <td>14:55 - 15:40</td>
                <td>Prototyping in the 4th Dimension <span class="speaker-name">Jakub Steiner</span</td>
                <td>GNOME in the Open Source Community <span class="speaker-name">Meg Ford</span</td>
                </tr>
            <tr>
                <td>15:40 - 16:20</td>
                <td class="break" colspan="2">Afternoon break</td>
            </tr>
            <tr>
                <td>16:20 - 17:05</td>
                <td>Sandboxed applications for GNOME <span class="speaker-name">Lennart Poettering</span</td>
                <td>GNOME Outreach: from Three Point Zero to Hero <span class="speaker-name">Sriram Ramkrishna</span</td>
            </tr>
            <tr>
                <td>17:15 - 18:00</td>
                <td>High resolution display support in GNOME <span class="speaker-name">Alex Larsson</span</td>
                <td>FLOSS Communities outreaches <span class="speaker-name">Flavia Weisghizzi</span</td>
            </tr>
        </table>

        <h3 id="day2" class="sub-section-title">Day 2: Friday, August 2st</h3>
        <table class="schedule">
            <tr>
                <td>09:00 - 10:00</td>
                <td class="keynote" colspan="2">Keynote @ Room 3</td>
            </tr>
            <tr>
                <td>10:00 - 10:40</td>
                <td class="break" colspan="2">Morning break</td>
            </tr>
            <tr>
                <td>10:40 - 11:25</td>
                <td>What's Cooking in GStreamer <span class="speaker-name">Sebastian Dröge and Tim-Philipp Müller</span</td>
                <td>Future in the Past: designing and implementing the GTK scene graph <span class="speaker-name">Emmanuele Bassi</span</td>
            </tr>
            <tr>
                <td>11:35 - 12:20</td>
                <td>Cogl: Having fun with GPU graphics <span class="speaker-name">Robert Bragg</span</td>
                <td>UI Developer Experience with Glade/GtkBuilder <span class="speaker-name">Tristan van Berkom</span</td>
            </tr>
            <tr>
                <td>12:30 - 14:00</td>
                <td class="lunch" colspan="2">Lunch</td>
            </tr>
            <tr>
                <td>14:00 - 14:45</td>
                <td>Compositing for Free: Reducing Copies on the Desktop <span class="speaker-name">Keith Packard</span</td>
                <td>GTK for application developers <span class="speaker-name">Matthias Clasen</span</td>
            </tr>
            <tr>
                <td>14:55 - 15:40</td>
                <td>Wayland: The future of Linux graphics is here <span class="speaker-name">Robert Bradford</span</td>
                <td>GTK: to infinity and beyond <span class="speaker-name">Benjamin Otte</span</td>
            </tr>
            <tr>
                <td>15:40 - 16:20</td>
                <td class="break" colspan="2">Afternoon break</td>
            </tr>
            <tr>
                <td>16:20 - 18:00</td>
                <td class="special-event" colspan="2">Foundation Annual General Meeting @ Room 3</td>
            </tr>
        </table>

        <h3 id="day3" class="sub-section-title">Day 3: Saturday, August 3rd</h3>
        <table class="schedule">
            <tr>
                <td>09:00 - 10:00</td>
                <td class="keynote" colspan="2">Keynote @ Room 3</td>
            </tr>
            <tr>
                <td>10:00 - 10:40</td>
                <td class="break" colspan="2">Morning break</td>
            </tr>
            <tr>
                <td>10:40 - 11:25</td>
                <td>Evolution as email service for the GNOME desktop <span class="speaker-name">Srinivasa Ragavan</td>
                <td>FOSS & Education <span class="speaker-name">Emily Gonyer</span</td>
            </tr>
            <tr>
                <td>11:35 - 12:20</td>
                <td>Testing online services <span class="speaker-name">Philip Withnall</td>
                <td>Gnome and ownCloud: desktop plus web for a holisic experience <span class="speaker-name">Jan-Christoph Borchardt</span</td>
            </tr>
            <tr>
                <td>12:30 - 14:00</td>
                <td class="lunch" colspan="2">Lunch</td>
            </tr>
            <tr>
                <td>14:00 - 14:45</td>
                <td>Clang, LLVM, and GNOME <span class="speaker-name">Bruno Cardoso Lopes</span</td>
                <td>Outreach Program for Women: a lesson in collaboration <span class="speaker-name">Marina Zhurakhinskaya</span</td>
            </tr>
            <tr>
                <td>14:55 - 15:40</td>
                <td>The Ways of Tracker <span class="speaker-name">Carlos Garnacho and Aleksander Morgado</span</td>
                <td>Documentation: State of the Union <span class="speaker-name">Ekaterina Gerasimova</span</td>
            </tr>
            <tr>
                <td>15:40 - 16:20</td>
                <td class="break" colspan="2">Afternoon break</td>
            </tr>
            <tr>
                <td>16:20 - 17:05</td><td>Writing multimedia applications with Grilo <span class="speaker-name">Juan A. Suarez</span</td>
                <td>Crowdfunding the GNOME Desktop: what we learned <span class="speaker-name">Jim Nelson</span</td>
            </tr>
            <tr>
                <td>17:15 - 18:00</td>
                <td>Extreme containment measures: keeping your bug reports under control <span class="speaker-name">Jeff Fortin</span</td>
                <td>The GNOME Infrastructure <span class="speaker-name">Sriram Ramkrishna and Andrea Veri</span</td>
            </tr>
        </table>

        <h3 id="day4" class="sub-section-title">Day 4: Sunday, August 4th</h3>
        <table class="schedule">
            <tr>
                <td>09:00 - 10:00</td>
                <td class="keynote" colspan="2">Keynote @ Room 3</td>
            </tr>
            <tr>
                <td>10:00 - 10:40</td>
                <td class="break" colspan="2">Morning break</td>
            </tr>
            <tr>
                <td>10:40 - 11:25</td>
                <td>Rich custom User Interfaces with Glade and CSS <span class="speaker-name">Juan Pablo Ugarte</span</td>
                <td>More secure with less “security” <span class="speaker-name">Stef Walter</span</td>
            </tr>
            <tr>
                <td>11:35 - 12:20</td>
                <td>Tag, your PDF is it <span class="speaker-name">Alejandro Piñeiro Iglesias and Joanmarie Diggs</span</td>
                <td>Predictive input methods: why? And how? <span class="speaker-name">Anish Patil and Mike Fabian</span</td>
            </tr>
            <tr>
                <td>12:30 - 14:00</td>
                <td class="lunch" colspan="2">Lunch</td>
            </tr>
            <tr>
                <td>14:00 - 14:45</td>
                <td>Webkit2 and you <span class="speaker-name">Martin Robinson and Carlos García Campos</span</td>
                <td>More than windows: a path through GTK+3 <span class="speaker-name">Marta Maria Casetti</span</td>
            </tr>
            <tr>
                <td>14:55 - 15:40</td>
                <td>Where am I? Where to grab lunch? <span class="speaker-name">Zeeshan Ali and Andreas Nilsson</span</td>
                <td>PiTiVi and GES, towards 1.x <span class="speaker-name">Jeff Fortin</span</td>
            </tr>
            <tr>
                <td>15:40 - 16:20</td>
                <td class="break" colspan="2">Afternoon break</td>
            </tr>
            <tr>
                <td>16:20 - 18:00</td>
                <td class="special-event" colspan="2">Lightning Talks @ Room 3</td>
            </tr>
        </table>


	</div><!-- #content -->
</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
