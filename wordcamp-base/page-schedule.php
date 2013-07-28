<?php
/*
Template Name: Schedule
*/

get_header(); ?>

<div id="container">
	<div id="content" role="main">
	    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	    <div class="entry-content">
            <?php the_content(); ?>
        </div>
        <?php endwhile; endif; ?>
	    
	   
	    <div class="schedule-nav">	        
    	    <a href="#day1">Day 1</a> | <a href="#day2">Day 2</a> | <a href="#day3">Day 3</a> | <a href="#day4">Day 4</a>
        </div>

        <h3 id="day1" class="sub-section-title">Day 1: Thursday, August 1st</h3>

        <table class="schedule">
            <tr>
                <td></td>
                <td class="room-name">E112</td>
                <td class="room-name">D0206</td>
            </tr>
        
            <tr>
                <td>09:00 - 10:00</td>
                <td class="keynote" colspan="2">Keynote: Ethan Lee <span style="float: right">D105</span></td>
            </tr>
            <tr>
                <td>10:00 - 10:40</td>
                <td class="break" colspan="2">Morning break</td>
            </tr>
            <tr>
                <td>10:40 - 11:25</td>
                <td><a class="talk" href="../session/the-future-of-gnome-3">The future of GNOME 3</a><span class="speaker-name">Allan Day</span></td>
                <td><a class="talk" href="../session/news-from-the-gnome-ostree-project">News from the GNOME OSTree project</a> <span class="speaker-name">Colin Walters</span></td>
            </tr>
            <tr>
                <td>11:35 - 12:20</td>
                <td><a class="talk" href="../session/first-steps-towards-contributing/">First steps towards contributing</a> <span class="speaker-name">Ekaterina Gerasimova and Sindhu Sundar</span></td>
                <td><a class="talk" href="../session/web-the-future-is-now/">Web, the future is now</a> <span class="speaker-name">Claudio Saavedra</span></td>
            </tr>
            <tr>
                <td>12:30 - 14:00</td>
                <td class="lunch" colspan="2">Lunch</td>
            </tr>
            <tr>
                <td>14:00 - 14:45</td>
                <td><a class="talk" href="../session/how-to-not-report-your-ux-bug/">How to not report your UX bug</a> <span class="speaker-name">Fabiana Simões</span></td>
                <td><a class="talk" href="../session/floss-communities-outreaches/">FLOSS Communities outreaches</a> <span class="speaker-name">Flavia Weisghizzi</span></td>
            </tr>
            <tr>
                <td>14:55 - 15:40</td>
                <td><a class="talk" href="../session/prototyping-in-the-4th-dimension/">Prototyping in the 4th dimension</a> <span class="speaker-name">Jakub Steiner</span></td>
                <td><a class="talk" href="../session/gnome-in-the-open-source-community/">GNOME in the Open Source community</a> <span class="speaker-name">Meg Ford</span></td>
                </tr>
            <tr>
                <td>15:40 - 16:20</td>
                <td class="break" colspan="2">Afternoon break</td>
            </tr>
            <tr>
                <td>16:20 - 17:05</td>
                <td><a class="talk" href="../session/sandboxed-applications-for-gnome/">Sandboxed applications for GNOME</a> <span class="speaker-name">Lennart Poettering</span></td>
                <td><a class="talk" href="../session/gnome-outreach-from-three-point-zero-to-hero/">GNOME Outreach: from three point zero to hero</a> <span class="speaker-name">Sriram Ramkrishna</span></td>
            </tr>
            <tr>
                <td>17:15 - 18:00</td>
                <td><a class="talk" href="../session/high-resolution-display-support-in-gnome/">High resolution display support in GNOME</a> <span class="speaker-name">Alex Larsson</span></td>
                <td><a class="talk" href="../session/libreoffice-gnome/">LibreOffice and GNOME</a> <span class="speaker-name">Michael Meeks</span></td>
            </tr>
        </table>
        <table class="schedule">
            <tr>
                <td>17:00 - 19:00</td>
                <td></td>
                <td><a class="talk" href="https://wiki.gnome.org/GUADEC/2013/NewcomersWorkshop">Newcomers Workshop</a> <span style="float: right">D0207</span></td>
            </tr>
        </table>

        <h3 id="day2" class="sub-section-title">Day 2: Friday, August 2st</h3>
        <table class="schedule">
            <tr>
                <td></td>
                <td class="room-name">E112</td>
                <td class="room-name">D0206</td>
            </tr>
            <tr>
                <td>09:00 - 10:00</td>
                <td class="keynote" colspan="2">Keynote: Matt Dalio <span style="float: right">D105</span></td>
            </tr>
            <tr>
                <td>10:00 - 10:40</td>
                <td class="break" colspan="2">Morning break</td>
            </tr>
            <tr>
                <td>10:40 - 11:25</td>
                <td><a class="talk" href="../session/whats-cooking-in-gstreamer/">What's cooking in GStreamer</a> <span class="speaker-name">Sebastian Dröge and Tim-Philipp Müller</span></td>
                <td><a class="talk" href="../session/future-in-the-past-designing-and-implementing-the-gtk-scene-graph/">Future in the past: designing and implementing the GTK scene graph</a> <span class="speaker-name">Emmanuele Bassi</span></td>
            </tr>
            <tr>
                <td>11:35 - 12:20</td>
                <td><a class="talk" href="../session/cogl-having-fun-with-gpu-graphics/">Cogl: Having fun with GPU graphics</a> <span class="speaker-name">Robert Bragg</span></td>
                <td><a class="talk" href="../session/ui-developer-experience-with-gladegtkbuilder/">UI developer experience with Glade/GtkBuilder</a> <span class="speaker-name">Tristan van Berkom</span></td>
            </tr>
            <tr>
                <td>12:30 - 14:00</td>
                <td class="lunch" colspan="2">Lunch</td>
            </tr>
            <tr>
                <td>14:00 - 14:45</td>
                <td><a class="talk" href="#day2">Panel discussion: Wayland, the road ahead</a> <span class="speaker-name">Kristian Høgsberg, Robert Bradford</span></td>
                <td><a class="talk" href="../session//gtk-for-application-developers/">GTK for application developers</a> <span class="speaker-name">Matthias Clasen</span></td>
            </tr>
            <tr>
                <td>14:55 - 15:40</td>
                <td><a class="talk" href="../session/wayland-the-future-of-linux-graphics-is-here/">Wayland: The future of Linux graphics is here</a> <span class="speaker-name">Robert Bradford</span></td>
                <td><a class="talk" href="../session/gtk-to-infinity-and-beyond/">GTK: to infinity and beyond</a> <span class="speaker-name">Benjamin Otte</span></td>
            </tr>
            <tr>
                <td>15:40 - 16:20</td>
                <td class="break" colspan="2">Afternoon break</td>
            </tr>
            <tr>
                <td>16:20 - 18:00</td>
                <td class="special-event" colspan="2">Foundation Annual General Meeting <span style="float: right">D105</span></td>
            </tr>
        </table>

        <h3 id="day3" class="sub-section-title">Day 3: Saturday, August 3rd</h3>
        <table class="schedule">
            <tr>
                <td></td>
                <td class="room-name">E112</td>
                <td class="room-name">D0206</td>
            </tr>
            <tr>
                <td>09:00 - 10:00</td>
                <td class="keynote" colspan="2">Q&A with the board <span style="float: right">D105</span></td>
            </tr>
            <tr>
                <td>10:00 - 10:40</td>
                <td class="break" colspan="2">Morning break</td>
            </tr>
            <tr>
                <td>10:40 - 11:25</td>
                <td><a class="talk" href="../session/evolution-as-email-service-for-the-gnome-desktop/">Evolution as email service for the GNOME desktop</a> <span class="speaker-name">Srinivasa Ragavan</td>
                <td><a class="talk" href="../session/foss-education/">FOSS & education</a> <span class="speaker-name">Emily Gonyer</span></td>
            </tr>
            <tr>
                <td>11:35 - 12:20</td>
                <td><a class="talk" href="../session/testing-online-services/">Testing online services</a> <span class="speaker-name">Philip Withnall</td>
                <td><a class="talk" href="../session/gnome-and-owncloud-desktop-plus-web-for-a-holisic-experience/">GNOME and ownCloud: desktop plus web for a holisic experience</a> <span class="speaker-name">Jan-Christoph Borchardt</span></td>
            </tr>
            <tr>
                <td>12:30 - 14:00</td>
                <td class="lunch" colspan="2">Lunch</td>
            </tr>
            <tr>
                <td>14:00 - 14:45</td>
                <td><a class="talk" href="../session/clang-llvm-and-gnome/">Clang, LLVM, and GNOME</a> <span class="speaker-name">Bruno Cardoso Lopes</span></td>
                <td><a class="talk" href="../session/outreach-program-for-women-lessons-in-collaboration/">Outreach Program for Women: a lesson in collaboration</a> <span class="speaker-name">Marina Zhurakhinskaya</span></td>
            </tr>
            <tr>
                <td>14:55 - 15:40</td>
                <td><a class="talk" href="../session/the-ways-of-tracker/">The ways of Tracker</a> <span class="speaker-name">Carlos Garnacho and Aleksander Morgado</span></td>
                <td><a class="talk" href="../session/documentation-state-of-the-union/">Documentation: state of the union</a> <span class="speaker-name">Ekaterina Gerasimova</span></td>
            </tr>
            <tr>
                <td>15:40 - 16:20</td>
                <td class="break" colspan="2">Afternoon break</td>
            </tr>
            <tr>
                <td>16:20 - 17:05</td>
                <td><a class="talk" href="../session/writing-multimedia-applications-with-grilo/">Writing multimedia applications with Grilo</a> <span class="speaker-name">Juan A. Suarez</span></td>
                <td>Empty</td>
            </tr>
            <tr>
                <td>17:15 - 18:00</td>
                <td><a class="talk" href="../session/extreme-containment-measures-keeping-your-bug-reports-under-control/">Extreme containment measures: keeping your bug reports under control</a> <span class="speaker-name">Jeff Fortin</span></td>
                <td><a class="talk" href="../session/the-gnome-infrastructure/">The GNOME infrastructure</a> <span class="speaker-name">Sriram Ramkrishna and Andrea Veri</span></td>
            </tr>
            <tr>
                <td>18:00 - 19:00</td>
                <td class="special-event" colspan="2">Lightning talks <span style="float: right">D105</span></td>
            </tr>
        </table>

        <h3 id="day4" class="sub-section-title">Day 4: Sunday, August 4th</h3>
        <table class="schedule">
            <tr>
                <td></td>
                <td class="room-name">E112</td>
                <td class="room-name">D0206</td>
            </tr>
            <tr>
                <td>09:00 - 10:00</td>
                <td class="keynote" colspan="2">Keynote: Cathy Malmrose <span style="float: right">D105</span></td>
            </tr>
            <tr>
                <td>10:00 - 10:40</td>
                <td class="break" colspan="2">Morning break</td>
            </tr>
            <tr>
                <td>10:40 - 11:25</td>
                <td><a class="talk" href="../session/rich-custom-user-interfaces-with-glade-and-css/">Rich custom user interfaces with Glade and CSS</a> <span class="speaker-name">Juan Pablo Ugarte</span></td>             <td><a class="talk" href="../session/tag-your-pdf-is-it/">Tag, your PDF is it</a> <span class="speaker-name">Alejandro Piñeiro Iglesias and Joanmarie Diggs</span></td>
            </tr>
            <tr>
                <td>11:35 - 12:20</td>
                <td><a class="talk" href="../session/more-secure-with-less-security/">More secure with less "security"</a> <span class="speaker-name">Stef Walter</span></td>
                <td><a class="talk" href="../session/predictive-input-methods-why-how/">Predictive input methods: why? And how?</a> <span class="speaker-name">Anish Patil and Mike Fabian</span></td>
            </tr>
            <tr>
                <td>12:30 - 14:00</td>
                <td class="lunch" colspan="2">Lunch</td>
            </tr>
            <tr>
                <td>14:00 - 14:45</td>
                <td><a class="talk" href="../session/webkit2-and-you/">Webkit2 and you</a> <span class="speaker-name">Martin Robinson and Carlos García Campos</span></td>
                <td><a class="talk" href="../session/more-than-windows-a-path-through-gtk3/">More than windows: a path through GTK+3</a> <span class="speaker-name">Marta Maria Casetti</span></td>
            </tr>
            <tr>
                <td>14:55 - 15:40</td>
                <td><a class="talk" href="../session/where-am-i-where-to-grab-lunch/">Where am I? Where to grab lunch?</a> <span class="speaker-name">Zeeshan Ali and Andreas Nilsson</span></td>
                <td><a class="talk" href="../session/pitivi-and-ges-towards-1-x/">PiTiVi and GES, towards 1.x</a> <span class="speaker-name">Jeff Fortin</span></td>
            </tr>
            <tr>
                <td>15:40 - 16:20</td>
                <td class="break" colspan="2">Afternoon break</td>
            </tr>
            <tr>
                <td>16:20 - 18:00</td>
                <td class="special-event" colspan="2">Interns lightning talks <span style="float: right">D105</span></td>
            </tr>
        </table>


	</div><!-- #content -->
</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
