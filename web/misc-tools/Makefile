ifndef TOPDIR
TOPDIR=..
endif
include $(TOPDIR)/Makefile.global

TARGETS =
OBJECTS =

SUBST_BRACKETSSERVER = save_sources2file restore_sources2db static_scoreboard \
                  combined_scoreboard judging_statistics balloons \
                  simulate_contest create_accounts multi_rejudge

SUBST_JUDGEHOST = create_cgroups dj_make_chroot

SUBST_FILES = $(SUBST_BRACKETSSERVER) $(SUBST_JUDGEHOST)

build: $(TARGETS) $(SUBST_FILES)

bracketsserver: $(SUBST_DOMSERVER)

judgehost: $(SUBST_JUDGEHOST)

$(SUBST_FILES): %: %.in $(TOPDIR)/paths.mk
	$(substconfigvars)

install-bracketsserver:
	$(INSTALL_PROG) -t $(DESTDIR)$(bracketsserver_bindir) $(SUBST_DOMSERVER)

install-judgehost:
	$(INSTALL_PROG) -t $(DESTDIR)$(judgehost_bindir) $(SUBST_JUDGEHOST) \
		dj_make_ubuntu_java_chroot

maintainer-install:
	for i in $(SUBST_BRACKETSSERVER) ; do \
		ln -sf $(CURDIR)/$$i $(bracketsserver_bindir) ; \
	done
	for i in $(SUBST_JUDGEHOST) dj_make_ubuntu_java_chroot ; do \
		ln -sf $(CURDIR)/$$i $(judgehost_bindir) ; \
	done

clean-l:
	-rm -f $(TARGETS) $(OBJECTS)

distclean-l:
	-rm -f $(SUBST_FILES)
