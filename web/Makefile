# Main Makefile

# Define TOPDIR from shell command and _not_ $(PWD) because that gives
# pwd before changing to a 'make -C <dir>' <dir>:
export TOPDIR = $(shell pwd)

REC_TARGETS=build domserver install-domserver judgehost install-judgehost \
            docs install-docs

# Global Makefile definitions
include $(TOPDIR)/Makefile.global

default:
	@echo "No default target"
	@echo
	@echo "Try:"
	@echo " - make all         (all targets below)"
	@echo " - make build       (all except 'docs')"
	@echo " - make domserver"
	@echo " - make judgehost"
	@echo " - make submitclient"
	@echo " - make docs"
	@echo or
	@echo " - make install-bracketsserver"
	@echo " - make install-judgehost"
	@echo " - make install-docs"
	@echo or
	@echo " - make clean"
	@echo " - make distclean"
	@exit 1

install:
	@echo "Try:"
	@echo " - make install-bracketsserver"
	@echo " - make install-judgehost"
	@echo " - make install-docs"
	@exit 1

all: build
build: bracketsserver judgehost

ifeq ($(SUBMITCLIENT_ENABLED),yes)
build: submitclient
endif

ifneq ($(DOC_BUILD_ENABLED),no)
all: docs
dist: distdocs
endif

# MAIN TARGETS
bracketsserver judgehost docs submitclient: paths.mk config
submitclient:
	$(MAKE) -C submit submitclient
install-bracketsserver: bracketsserver bracketsserver-create-dirs
install-judgehost: judgehost judgehost-create-dirs
install-docs: docs-create-dirs
dist: configure

# Install PHP dependencies
dist: composer-dependencies
composer-dependencies:
ifeq (, $(shell which composer))
	$(error "'composer' command not found in $(PATH), install it https://getcomposer.org/download/")
endif
	composer $(subst 1,-q,$(QUIET)) install --no-dev

# Generate documentation for distribution. Remove this dependency from
# dist above for quicker building from git sources.
distdocs:
	$(MAKE) -C doc distdocs

# Generate configuration files from configure settings:
config:
	$(MAKE) -C etc config

# Special rule to build any compile/run/compare scripts. This is
# useful for testing, e.g. when submitting a Coverity scan.
build-scripts:
	$(MAKE) -C sql build-scripts

# List of SUBDIRS for recursive targets:
build:             SUBDIRS=        lib                      tests misc-tools
bracketsserver:         SUBDIRS=etc         sql www                    misc-tools
install-bracketsserver: SUBDIRS=etc     lib sql www                    misc-tools
judgehost:         SUBDIRS=etc                 judge              misc-tools
install-judgehost: SUBDIRS=etc     lib         judge              misc-tools
docs:              SUBDIRS=    doc
install-docs:      SUBDIRS=    doc         www
dist:              SUBDIRS=        lib sql                        misc-tools
clean:             SUBDIRS=etc doc lib sql www judge submit tests misc-tools
distclean:         SUBDIRS=etc doc lib sql www judge submit tests misc-tools
maintainer-clean:  SUBDIRS=etc doc lib sql www judge submit tests misc-tools

bracketsserver-create-dirs:
	$(INSTALL_DIR) $(addprefix $(DESTDIR),$(bracketsserver_dirs))

judgehost-create-dirs:
	$(INSTALL_DIR) $(addprefix $(DESTDIR),$(judgehost_dirs))

docs-create-dirs:
	$(INSTALL_DIR) $(addprefix $(DESTDIR),$(docs_dirs))

install-docs-l:
	$(INSTALL_DATA) -t $(DESTDIR)$(bracketsjudge_docdir) README.md ChangeLog COPYING*

# As final step try set ownership and permissions of a few special
# files/directories. Print a warning and fail gracefully if this
# doesn't work because we're not root.
install-bracketsserver-l:
	$(MAKE) check-root
# Fix permissions for special directories (don't touch tmpdir when FHS enabled):
	-$(INSTALL_USER)    -m 0700 -d $(DESTDIR)$(bracketsserver_logdir)
	-$(INSTALL_USER)    -m 0700 -d $(DESTDIR)$(bracketsserver_rundir)
	-$(INSTALL_WEBSITE) -m 0770 -d $(DESTDIR)$(bracketsserver_submitdir)
ifneq "$(FHS_ENABLED)" "yes"
	-$(INSTALL_WEBSITE) -m 0770 -d $(DESTDIR)$(bracketsserver_tmpdir)
endif
# Fix permissions and ownership for password files:
	-$(INSTALL_USER) -m 0600 -t $(DESTDIR)$(bracketsserver_etcdir) \
		etc/restapi.secret
	-$(INSTALL_WEBSITE) -m 0640 -t $(DESTDIR)$(bracketsserver_etcdir) \
		etc/dbpasswords.secret

install-judgehost-l:
	$(MAKE) check-root
# Fix permissions for special directories (don't touch tmpdir when FHS enabled):
	-$(INSTALL_USER) -m 0700 -d $(DESTDIR)$(judgehost_logdir)
	-$(INSTALL_USER) -m 0700 -d $(DESTDIR)$(judgehost_rundir)
	-$(INSTALL_USER) -m 0711 -d $(DESTDIR)$(judgehost_judgedir)
ifneq "$(FHS_ENABLED)" "yes"
	-$(INSTALL_USER) -m 0700 -d $(DESTDIR)$(judgehost_tmpdir)
endif
# Fix permissions and ownership for password files:
	-$(INSTALL_USER) -m 0600 -t $(DESTDIR)$(judgehost_etcdir) \
		etc/restapi.secret

check-root:
	@if [ `id -u` -ne 0 -a -z "$(QUIET)" ]; then \
		echo "**************************************************************" ; \
		echo "***  You do not seem to have the required root privileges. ***" ; \
		echo "***       Perform any failed commands below manually.      ***" ; \
		echo "**************************************************************" ; \
	fi

dist-l:
	$(MAKE) clean-autoconf

# Run aclocal separately from autoreconf, which doesn't pass -I option.
aclocal.m4: configure.ac $(wildcard m4/*.m4)
	aclocal -I m4

configure: configure.ac aclocal.m4
	autoheader
	autoconf

paths.mk:
	@echo "The file 'paths.mk' is not available. Probably you"
	@echo "have not run './configure' yet, aborting..."
	@exit 1

# Configure for running in source tree, not meant for normal use:
MAINT_CXFLAGS=-g -O1 -Wall -fstack-protector -D_FORTIFY_SOURCE=2 \
              -fPIE -Wformat -Wformat-security -ansi -pedantic
MAINT_LDFLAGS=-fPIE -pie -Wl,-z,relro -Wl,-z,now
maintainer-conf: configure
	./configure $(subst 1,-q,$(QUIET)) --prefix=$(CURDIR) \
	            --with-bracketsserver_root=$(CURDIR) \
	            --with-judgehost_root=$(CURDIR) \
	            --with-bracketsjudge_docdir=$(CURDIR)/doc \
	            --with-bracketsserver_logdir=$(CURDIR)/output/log \
	            --with-judgehost_logdir=$(CURDIR)/output/log \
	            --with-bracketsserver_rundir=$(CURDIR)/output/run \
	            --with-judgehost_rundir=$(CURDIR)/output/run \
	            --with-bracketsserver_tmpdir=$(CURDIR)/output/tmp \
	            --with-judgehost_tmpdir=$(CURDIR)/output/tmp \
	            --with-judgehost_judgedir=$(CURDIR)/output/judgings \
	            --with-bracketsserver_submitdir=$(CURDIR)/output/submissions \
	            --with-baseurl='http://localhost/brackets/' \
	            CFLAGS='$(MAINT_CXFLAGS)' \
	            CXXFLAGS='$(MAINT_CXFLAGS)' \
	            LDFLAGS='$(MAINT_LDFLAGS)' \
	            $(CONFIGURE_FLAGS)

# Install the system in place: don't really copy stuff, but create
# symlinks where necessary to let it work from the source tree.
# This stuff is a hack!
maintainer-install: dist build bracketsserver-create-dirs judgehost-create-dirs
# Replace lib{judge,submit}dir with symlink to prevent lots of symlinks:
	-rmdir $(judgehost_libjudgedir) $(bracketsserver_libsubmitdir)
	-rm -f $(judgehost_libjudgedir) $(bracketsserver_libsubmitdir)
	ln -sf $(CURDIR)/judge  $(judgehost_libjudgedir)
	ln -sf $(CURDIR)/submit $(bracketsserver_libsubmitdir)
	ln -sfn $(CURDIR)/doc $(bracketsserver_wwwdir)/jury/doc
# Add symlinks to binaries:
	$(MKDIR_P) $(judgehost_bindir) $(bracketsserver_bindir)
	ln -sf $(CURDIR)/judge/judgedaemon $(judgehost_bindir)
	ln -sf $(CURDIR)/judge/runguard $(judgehost_bindir)
	ln -sf $(CURDIR)/judge/runpipe  $(judgehost_bindir)
	ln -sf $(CURDIR)/sql/dj_setup_database $(bracketsserver_bindir)
	$(MAKE) -C misc-tools maintainer-install
# Make tmpdir, submitdir writable for webserver, because
# judgehost-create-dirs sets wrong permissions:
	chmod a+rwx $(bracketsserver_tmpdir) $(bracketsserver_submitdir)
	@echo "Make sure that etc/dbpasswords.secret is readable by the webserver!"

# Removes created symlinks; generated logs, submissions, etc. remain in output subdir.
maintainer-uninstall:
	rm -f $(judgehost_libjudgedir) $(bracketsserver_libsubmitdir)
	rm -f $(bracketsserver_wwwdir)/jury/doc
	rm -rf $(judgehost_bindir)

# Rules to configure and build for a Coverity scan.
coverity-conf:
	$(MAKE) maintainer-conf

coverity-build: paths.mk
	$(MAKE) build build-scripts
	@VERSION=` grep '^VERSION ='   paths.mk | sed 's/^VERSION = *//'` ; \
	PUBLISHED=`grep '^PUBLISHED =' paths.mk | sed 's/^PUBLISHED = *//'` ; \
	if [ "$$PUBLISHED" = release ]; then DESC="release" ; \
	elif [ -n "$$PUBLISHED" ];      then DESC="snapshot $$PUBLISHED" ; \
	elif [ ! -d .git ];             then DESC="unknown source on `date`" ; fi ; \
	echo "VERSION=$$VERSION" > cov-submit-data-version.sh ; \
	if [ -n "$$DESC" ]; then echo "DESC=$$DESC" >> cov-submit-data-version.sh ; fi

clean-l:
# Remove Coverity scan data:
	-rm -rf cov-int bracketsjudge-scan.t* coverity-scan.tar.xz cov-submit-data-version.sh

distclean-l: clean-autoconf
	-rm -f paths.mk

maintainer-clean-l:
	-rm -f configure aclocal.m4

clean-autoconf:
	-rm -rf config.status config.cache config.log autom4te.cache

.PHONY: $(addsuffix -create-dirs,bracketsserver judgehost docs) check-root \
        clean-autoconf $(addprefix maintainer-,conf install uninstall) \
        config submitclient distdocs composer-dependencies \
        coverity-conf coverity-build
